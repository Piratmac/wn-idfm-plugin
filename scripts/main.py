#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import idfm_database, idfm_webservice
import getopt, sys

db_details = {'host': 'localhost', 'port': 3306, 'user': '', 'password': '', 'database': ''}
try:
  opts, args = getopt.getopt(sys.argv[1:], "", ["host=", "username=", "password=", "database=", "port=", 'api_key='])
  for opt, arg in opts:
    if opt[2:] == 'username':
      db_details['user'] = arg
    elif opt[2:] in db_details:
      db_details[opt[2:]] = arg
    elif opt[2:] == 'api_key':
      api_key = arg
except getopt.GetoptError as err:
  sys.exit('Missing arguments.' + str(err))

idfm_database = idfm_database.Idfm_Database(db_details)
idfm_webservice = idfm_webservice.Idfm_WebService(api_key)

monitored_stops = idfm_database.get_monitored_stops()

visits_data = []
for monitored_stop_id in monitored_stops:
  # Get results in raw format from webservice
  idfm_results = idfm_webservice.get_visits_for_station(monitored_stops[monitored_stop_id]['stop_idfm_id'])

  # Preparing structure for visits
  record_time = idfm_results['Siri']['ServiceDelivery']['ResponseTimestamp']
  line_id     = monitored_stops[monitored_stop_id]['line_id']
  stop_id     = monitored_stops[monitored_stop_id]['stop_id']
  error       = ''
  no_visit_for_stop = True

  #Loop on each visit (if any)
  if 'StopMonitoringDelivery' in idfm_results['Siri']['ServiceDelivery']:
    if 'ErrorCondition' in idfm_results['Siri']['ServiceDelivery']:
      error = idfm_results['Siri']['ServiceDelivery']['ErrorCondition']
    if 'MonitoredStopVisit' in idfm_results['Siri']['ServiceDelivery']['StopMonitoringDelivery'][0]:
      if len(idfm_results['Siri']['ServiceDelivery']['StopMonitoringDelivery'][0]['MonitoredStopVisit']) != 0:
        no_visit_for_stop = False
      for visit in idfm_results['Siri']['ServiceDelivery']['StopMonitoringDelivery'][0]['MonitoredStopVisit']:
# The filter on ignored destinations has been moved to OctoberCMS
#        if not visit['MonitoredVehicleJourney']['DestinationRef']['value'] in monitored_stops[monitored_stop_id]['ignored_destination_id']:
        visits_data.append({'visit_id': visit['ItemIdentifier'],
#                            'monitored_stop_id': monitored_stop_id,
                            'record_time': record_time,
                            'line_id': line_id,
                            'stop_id': stop_id,
                            'destination_id': idfm_database.get_station_id(visit['MonitoredVehicleJourney']['DestinationRef']['value']),
                            'at_stop': visit['MonitoredVehicleJourney']['MonitoredCall']['VehicleAtStop'],
                            'departure_time': visit['MonitoredVehicleJourney']['MonitoredCall']['ExpectedDepartureTime'] if 'ExpectedDepartureTime' in visit['MonitoredVehicleJourney']['MonitoredCall'] else '',
                            'error_message': error + (''  if 'ExpectedDepartureTime' in visit['MonitoredVehicleJourney']['MonitoredCall'] else 'No departure time')
                            })
# The filter on ignored destinations has been moved to OctoberCMS. The below line has been moved up for efficiency
#        no_visit_for_stop = False
  if no_visit_for_stop:
    visits_data.append({'record_time': record_time,
#                        'monitored_stop_id': monitored_stop_id,
                        'line_id': line_id,
                        'stop_id': stop_id,
                        'visit_id': '',
                        'destination_id': '',
                        'at_stop': False,
                        'departure_time': '',
                        'error_message': 'No stops received. ' + ('Error message: ' + error if len(error) != 0 else 'No error from IDFM.')
                        })

idfm_database.set_visits(visits_data)
