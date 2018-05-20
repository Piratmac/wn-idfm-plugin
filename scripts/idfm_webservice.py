#!/usr/bin/env python3
# -*- coding: utf-8 -*-


import requests, json

class Idfm_WebService:
  def __init__ (self, api_key):
    self.api_key = api_key

  def get_visits_for_station(self, stop_idfm_id):
    url = "https://api-lab-trone-stif.opendata.stif.info/service/tr-unitaire-stif/stop-monitoring?apikey=" + self.api_key + "&MonitoringRef=" + str(stop_idfm_id)
    return json.loads(requests.get(url).text)
