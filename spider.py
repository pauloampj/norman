# -*- coding: utf-8 -*-
import scrapy
import json

class BlogSpider(scrapy.Spider):
    name = 'blogspider'
    start_urls = ['http://www.bcb.gov.br/pre/normativos/busca/buscaSharePoint.asp?dataInicioBusca=18/02/2017&dataFimBusca=20/02/2017&startRow=0']

    def parse(self, response):
        json_data = json.loads(response.body) 

	item = MyItem()
        item["d"] = jsonresponse["d"]             

        return item
