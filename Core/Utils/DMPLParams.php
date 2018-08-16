<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Parametros - Cria classe estática para armazenamento de 	   **
**				  parâmetros.												   **
** @Namespace	: Damaplan\Norman\Core\Utils								   **
** @Copyright	: Damaplan Consultoria LTDA (http://www.damaplan.com.br)       **
** @Link		: http://norman.damaplan.com.br/documentation                  **
** @Email		: sistemas@damaplan.com.br					                   **
** @Observation : Esta ferramenta e seu inteiro teor é de propriedade da	   **
**				  Damaplan Consultoria e Estratégia LTDA. Não é permitida sua  **
**				  edição, distribuição ou divulgação sem prévia autorização.   **
** --------------------------------------------------------------------------- **
** @Developer	:                                                              **
** @Date	 	:                                                     	       **
** @Version	 	:                                                     	       **
** @Comment	 	:                                                              **
** --------------------------------------------------------------------------- **
** @Developer	: @pauloampj                                                   **
** @Date	 	: 28/06/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

namespace Damaplan\Norman\Core\Utils;


class DMPLParams {

    protected static $_values = [
        'debug' => 0
    ];

    public static function _hashGet($data, $path, $default = null) {
    	if (!(is_array($data) || $data instanceof ArrayAccess)) {
    		throw new InvalidArgumentException(
    				'Invalid data type, must be an array or \ArrayAccess instance.'
    				);
    	}
    	
    	if (empty($data) || $path === null || $path === '') {
    		return $default;
    	}
    	
    	if (is_string($path) || is_numeric($path)) {
    		$parts = explode('.', $path);
    	} else {
    		if (!is_array($path)) {
    			throw new InvalidArgumentException(sprintf(
    					'Invalid Parameter %s, should be dot separated path or array.',
    					$path
    					));
    		}
    		
    		$parts = $path;
    	}
    	
    	switch (count($parts)) {
    		case 1:
    			return isset($data[$parts[0]]) ? $data[$parts[0]] : $default;
    		case 2:
    			return isset($data[$parts[0]][$parts[1]]) ? $data[$parts[0]][$parts[1]] : $default;
    		case 3:
    			return isset($data[$parts[0]][$parts[1]][$parts[2]]) ? $data[$parts[0]][$parts[1]][$parts[2]] : $default;
    		default:
    			foreach ($parts as $key) {
    				if ((is_array($data) || $data instanceof ArrayAccess) && isset($data[$key])) {
    					$data = $data[$key];
    				} else {
    					return $default;
    				}
    			}
    	}
    	
    	return $data;
    }
    
    public static function _hashInsert(array $data, $path, $values = null) {
    	$noTokens = strpos($path, '[') === false;
    	if ($noTokens && strpos($path, '.') === false) {
    		$data[$path] = $values;
    		return $data;
    	}
    	
    	if ($noTokens) {
    		$tokens = explode('.', $path);
    	} else {
    		$tokens = _textTokenize($path, '.', '[', ']');
    	}
    	
    	if ($noTokens && strpos($path, '{') === false) {
    		return static::_simpleOp('insert', $data, $tokens, $values);
    	}
    	
    	$token = array_shift($tokens);
    	$nextPath = implode('.', $tokens);
    	
    	list($token, $conditions) = static::_splitConditions($token);
    	
    	foreach ($data as $k => $v) {
    		if (static::_matchToken($k, $token)) {
    			if (!$conditions || static::_matches($v, $conditions)) {
    				$data[$k] = $nextPath
    				? static::_hashInsert($v, $nextPath, $values)
    				: array_merge($v, (array)$values);
    			}
    		}
    	}
    	return $data;
    }
    
    public static function _textTokenize($data, $separator = ',', $leftBound = '(', $rightBound = ')') {
    	if (empty($data)) {
    		return [];
    	}
    	
    	$depth = 0;
    	$offset = 0;
    	$buffer = '';
    	$results = [];
    	$length = mb_strlen($data);
    	$open = false;
    	
    	while ($offset <= $length) {
    		$tmpOffset = -1;
    		$offsets = [
    				mb_strpos($data, $separator, $offset),
    				mb_strpos($data, $leftBound, $offset),
    				mb_strpos($data, $rightBound, $offset)
    		];
    		for ($i = 0; $i < 3; $i++) {
    			if ($offsets[$i] !== false && ($offsets[$i] < $tmpOffset || $tmpOffset == -1)) {
    				$tmpOffset = $offsets[$i];
    			}
    		}
    		if ($tmpOffset !== -1) {
    			$buffer .= mb_substr($data, $offset, ($tmpOffset - $offset));
    			$char = mb_substr($data, $tmpOffset, 1);
    			if (!$depth && $char === $separator) {
    				$results[] = $buffer;
    				$buffer = '';
    			} else {
    				$buffer .= $char;
    			}
    			if ($leftBound !== $rightBound) {
    				if ($char === $leftBound) {
    					$depth++;
    				}
    				if ($char === $rightBound) {
    					$depth--;
    				}
    			} else {
    				if ($char === $leftBound) {
    					if (!$open) {
    						$depth++;
    						$open = true;
    					} else {
    						$depth--;
    					}
    				}
    			}
    			$offset = ++$tmpOffset;
    		} else {
    			$results[] = $buffer . mb_substr($data, $offset);
    			$offset = $length + 1;
    		}
    	}
    	if (empty($results) && !empty($buffer)) {
    		$results[] = $buffer;
    	}
    	
    	if (!empty($results)) {
    		return array_map('trim', $results);
    	}
    	
    	return [];
    }
    
    protected static function _splitConditions($token) {
    	$conditions = false;
    	$position = strpos($token, '[');
    	if ($position !== false) {
    		$conditions = substr($token, $position);
    		$token = substr($token, 0, $position);
    	}
    	
    	return [$token, $conditions];
    }
    
    protected static function _simpleOp($op, $data, $path, $values = null) {
    	$_list =& $data;
    	
    	$count = count($path);
    	$last = $count - 1;
    	foreach ($path as $i => $key) {
    		if ((is_numeric($key) && (int)($key) > 0 || $key === '0') &&
    				strpos($key, '0') !== 0
    				) {
    					$key = (int)$key;
    				}
    				if ($op === 'insert') {
    					if ($i === $last) {
    						$_list[$key] = $values;
    						return $data;
    					}
    					if (!isset($_list[$key])) {
    						$_list[$key] = [];
    					}
    					$_list =& $_list[$key];
    					if (!is_array($_list)) {
    						$_list = [];
    					}
    				} elseif ($op === 'remove') {
    					if ($i === $last) {
    						unset($_list[$key]);
    						return $data;
    					}
    					if (!isset($_list[$key])) {
    						return $data;
    					}
    					$_list =& $_list[$key];
    				}
    	}
    }
    
    public static function _hashRemove(array $data, $path) {
    	$noTokens = strpos($path, '[') === false;
    	$noExpansion = strpos($path, '{') === false;
    	
    	if ($noExpansion && $noTokens && strpos($path, '.') === false) {
    		unset($data[$path]);
    		return $data;
    	}
    	
    	$tokens = $noTokens ? explode('.', $path) : _textTokenize($path, '.', '[', ']');
    	
    	if ($noExpansion && $noTokens) {
    		return static::_simpleOp('remove', $data, $tokens);
    	}
    	
    	$token = array_shift($tokens);
    	$nextPath = implode('.', $tokens);
    	
    	list($token, $conditions) = self::_splitConditions($token);
    	
    	foreach ($data as $k => $v) {
    		$match = static::_matchToken($k, $token);
    		if ($match && is_array($v)) {
    			if ($conditions) {
    				if (static::_matches($v, $conditions)) {
    					if ($nextPath) {
    						$data[$k] = static::remove($v, $nextPath);
    					} else {
    						unset($data[$k]);
    					}
    				}
    			} else {
    				$data[$k] = static::remove($v, $nextPath);
    			}
    			if (empty($data[$k])) {
    				unset($data[$k]);
    			}
    		} elseif ($match && empty($nextPath)) {
    			unset($data[$k]);
    		}
    	}
    	return $data;
    }
    
    protected static function _matches($data, $selector) {
    	preg_match_all(
    			'/(\[ (?P<attr>[^=><!]+?) (\s* (?P<op>[><!]?[=]|[><]) \s* (?P<val>(?:\/.*?\/ | [^\]]+)) )? \])/x',
    			$selector,
    			$conditions,
    			PREG_SET_ORDER
    			);
    	
    	foreach ($conditions as $cond) {
    		$attr = $cond['attr'];
    		$op = isset($cond['op']) ? $cond['op'] : null;
    		$val = isset($cond['val']) ? $cond['val'] : null;
    		
    		// Presence test.
    		if (empty($op) && empty($val) && !isset($data[$attr])) {
    			return false;
    		}
    		
    		// Empty attribute = fail.
    		if (!(isset($data[$attr]) || array_key_exists($attr, $data))) {
    			return false;
    		}
    		
    		$prop = null;
    		if (isset($data[$attr])) {
    			$prop = $data[$attr];
    		}
    		$isBool = is_bool($prop);
    		if ($isBool && is_numeric($val)) {
    			$prop = $prop ? '1' : '0';
    		} elseif ($isBool) {
    			$prop = $prop ? 'true' : 'false';
    		}
    		
    		// Pattern matches and other operators.
    		if ($op === '=' && $val && $val[0] === '/') {
    			if (!preg_match($val, $prop)) {
    				return false;
    			}
    		} elseif (($op === '=' && $prop != $val) ||
    				($op === '!=' && $prop == $val) ||
    				($op === '>' && $prop <= $val) ||
    				($op === '<' && $prop >= $val) ||
    				($op === '>=' && $prop < $val) ||
    				($op === '<=' && $prop > $val)
    				) {
    					return false;
    		}
    		
    	}
    	return true;
    }
    
    public static function write($config, $value = null) {
        if (!is_array($config)) {
            $config = [$config => $value];
        }

        foreach ($config as $name => $value) {
            static::$_values = static::_hashInsert(static::$_values, $name, $value);
        }

        return true;
    }

    public static function read($var = null) {
        if ($var === null) {
            return static::$_values;
        }
        return static::_hashGet(static::$_values, $var);
    }

    public static function check($var) {
        if (empty($var)) {
            return false;
        }
        return static::read($var) !== null;
    }

    public static function delete($var) {
        static::$_values = static::_hashRemove(static::$_values, $var);
    }

    public static function consume($var) {
        if (strpos($var, '.') === false) {
            if (!isset(static::$_values[$var])) {
                return null;
            }
            $value = static::$_values[$var];
            unset(static::$_values[$var]);
            return $value;
        }
        $value = static::_hashGet(static::$_values, $var);
        static::delete($var);
        return $value;
    }

    public static function clear() {
        static::$_values = [];
        return true;
    }
}
