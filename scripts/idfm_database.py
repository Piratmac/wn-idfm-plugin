#!/usr/bin/env python3
# -*- coding: utf-8 -*-


import mysql.connector
import sys

class Idfm_Database:
  def __init__ (self, db_details):
    try:
      self.conn = mysql.connector.connect(**db_details)
    except mysql.connector.Error as err:
      sys.exit('Could not connect to database. Error: ' + str(err))
    del db_details
    self.stations_list = {}
    self.stations_idfm_to_database = {}
    self.cursor = self.conn.cursor()

  # This converts the database ID (integer) to the label (name) of the stop
  def get_station_name (self, stop_id):
    if not stop_id in self.stations_list:
      self._get_station_from_database (stop_id)
    return self.stations_list[stop_id]['name']

  # This converts the database ID (integer) to the IDFM ID ('STIF:StopPoint:Q:412844:')
  def get_station_idfm_id (self, stop_id):
    if not stop_id in self.stations_list:
      self._get_station_from_database (stop_id)
    return self.stations_list[stop_id]['idfm_id']

  # This converts the IDFM ID ('STIF:StopPoint:Q:412844:') to the database ID (integer)
  def get_station_id (self, stop_idfm_id):
    if not stop_idfm_id in self.stations_idfm_to_database:
      self._get_station_from_database (stop_idfm_id, 'idfm_id')
    return self.stations_idfm_to_database[stop_idfm_id]

  # This gets the data for a stop based on its database ID
  def _get_station_from_database (self, stop_id, id_type = 'id'):
    if not id_type in ('id', 'idfm_id'):
      id_type = 'id'
    self.cursor.execute("SELECT id, name, idfm_id FROM piratmac_idfm_stops WHERE " + id_type + " = %s", (stop_id, ))
    result = self.cursor.fetchall()
    self.stations_list[stop_id] = {'name': result[0][1], 'idfm_id': result[0][2]}
    self.stations_idfm_to_database[result[0][2]] = result[0][0]


  def get_monitored_stops (self):
    self.cursor.execute("SELECT stop.monitored_stop_id, stop_id, line_id, ignored_destination_id FROM piratmac_idfm_monitored_stop stop LEFT JOIN piratmac_idfm_monitored_stop_ignored_destination dest ON stop.monitored_stop_id = dest.monitored_stop_id")
    dbresults = self.cursor.fetchall()
    monitored_stops = {}
    for dbresult in dbresults:
      if not dbresult[0] in monitored_stops:
        monitored_stops[dbresult[0]] = {'stop_idfm_id': self.get_station_idfm_id(dbresult[1]), 'stop_id': dbresult[1], 'line_id': dbresult[2]};
        monitored_stops[dbresult[0]]['ignored_destination_id'] = [];
      if not dbresult[3] is None:
        monitored_stops[dbresult[0]]['ignored_destination_id'].append(self.get_station_idfm_id(dbresult[3]))
    return monitored_stops


  def set_visits (self, values_to_insert):
    items_list = []
    visit_id_list = []
    for value in values_to_insert:
      items_list.append([
  #      value['monitored_stop_id'],
        value['visit_id'],
        value['record_time'],
        value['line_id'],
        value['stop_id'],
        value['destination_id'],
        value['at_stop'],
        value['departure_time'],
        value['error_message'],
      ])
      self.cursor.execute("DELETE FROM piratmac_idfm_visits WHERE visit_id = %s", (value['visit_id'],))

    self.cursor.executemany("INSERT INTO piratmac_idfm_visits (visit_id, record_time, line_id, stop_id, destination_id, at_stop, departure_time, error_message) VALUES (%s, STR_TO_DATE(%s, '%Y-%m-%dT%TZ'), %s, %s, %s, %s, STR_TO_DATE(%s, '%Y-%m-%dT%T.%fZ'), %s)", items_list)
    self.conn.commit()

