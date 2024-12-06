<?php 
// include ("koneksi.php");
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$json_data2='

{
  "resourceType": "Bundle",
  "type": "transaction",
  "entry": [
    {
      "fullUrl": "urn:uuid:5bc05fa8-fba4-4b1b-8a36-a31ba3b3cdce",
      "resource": {
        "resourceType": "Encounter",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/encounter/10085103",
            "value": "10085103"
          }
        ],
        "status": "finished",
        "class": {
          "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
          "code": "IMP",
          "display": "inpatient encounter"
        },
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "participant": [
          {
            "type": [
              {
                "coding": [
                  {
                    "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                    "code": "ATND",
                    "display": "attender"
                  }
                ]
              }
            ],
            "individual": {
              "reference": "Practitioner/N10000001",
              "display": "Dokter Bronsig"
            },
            "period": {
              "start": "2023-05-26T08:00:00+00:00",
              "end": "2023-05-30T15:30:27+07:00"
            }
          }
        ],
        "period": {
          "start": "2023-05-26T08:00:00+00:00",
          "end": "2023-05-30T15:30:27+07:00"
        },
        "location": [
          {
            "location": {
              "reference": "Location/b29038d4-9ef0-4eb3-a2e9-3c02df668b07",
              "display": "Bed 2, Ruang 210, Bangsal Rawat Inap Kelas 1, Layanan Penyakit Dalam, Lantai 2, Gedung Utama"
            },
            "extension": [
              {
                "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass",
                "extension": [
                  {
                    "url": "value",
                    "valueCodeableConcept": {
                      "coding": [
                        {
                          "system": "http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient",
                          "code": "1",
                          "display": "Kelas 1"
                        }
                      ]
                    }
                  },
                  {
                    "url": "upgradeClassIndicator",
                    "valueCodeableConcept": {
                      "coding": [
                        {
                          "system": "http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass",
                          "code": "kelas-tetap",
                          "display": "Kelas Tetap Perawatan"
                        }
                      ]
                    }
                  }
                ]
              }
            ]
          }
        ],
        "diagnosis": [
          {
            "condition": {
              "reference": "Condition/a734df17-84ca-4a09-998c-95442eba13d9",
              "display": "Chronic kidney disease, stage 5"
            },
            "use": {
              "coding": [
                {
                  "system": "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                  "code": "DD",
                  "display": "Discharge diagnosis"
                }
              ]
            },
            "rank": 1
          },
          {
            "condition": {
              "reference": "Condition/23cc164d-9e49-4d6c-b46b-576efbb123fe",
              "display": "Anemia in chronic kidney disease"
            },
            "use": {
              "coding": [
                {
                  "system": "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                  "code": "DD",
                  "display": "Discharge diagnosis"
                }
              ]
            },
            "rank": 2
          }
        ],
        "statusHistory": [
          {
            "status": "in-progress",
            "period": {
              "start": "2023-05-26T08:00:00+00:00",
              "end": "2023-05-30T15:30:00+00:00"
            }
          },
          {
            "status": "finished",
            "period": {
              "start": "2023-05-30T15:30:00+00:00",
              "end": "2023-05-30T15:30:00+00:00"
            }
          }
        ],
        "serviceProvider": {
          "reference": "Organization/10085103"
        }
      },
      "request": {
        "method": "POST",
        "url": "Encounter"
      }
    },
    {
      "fullUrl": "urn:uuid:15ffea7d-9171-4572-8d5f-246cf4cd4473",
      "resource": {
        "resourceType": "CarePlan",
        "title": "Rencana Rawat Pasien",
        "status": "active",
        "category": [
          {
            "coding": [
              {
                "system": "http://snomed.info/sct",
                "code": "736353004",
                "display": " Inpatient care plan"
              }
            ]
          }
        ],
        "intent": "plan",
        "description": "Pasien akan melakukan Pengecekan Kolesterol Darah dan Proses CT-Scan serta Tindakan Hemodialisis dengan Rencana Lama Waktu Rawat selama 3-4 Hari",
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "created": "2023-05-27T08:00:00+00:00",
        "author": {
          "reference": "Practitioner/N10000001"
        }
      },
      "request": {
        "method": "POST",
        "url": "CarePlan"
      }
    },
    {
      "fullUrl": "urn:uuid:194c9b32-788b-4110-59ea-7161aa33cf68",
      "resource": {
        "resourceType": "CarePlan",
        "title": "Instruksi Medik dan Keperawatan Pasien",
        "status": "active",
        "intent": "plan",
        "category": [
          {
            "coding": [
              {
                "system": "http://snomed.info/sct",
                "code": "736353004",
                "display": " Inpatient care plan"
              }
            ]
          }
        ],
        "description": "Penanganan Anemia Pasien dilakukan dengan pemberian hormone eritropoitin, transfusi darah, dan vitamin.",
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "created": "2023-05-27T08:00:00+00:00",
        "author": {
          "reference": "Practitioner/N10000001"
        }
      },
      "request": {
        "method": "POST",
        "url": "CarePlan"
      }
    },
    {
      "fullUrl": "urn:uuid:196e33ca-6673-4ae0-acd9-1f03a95d7b32",
      "resource": {
        "resourceType": "ServiceRequest",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/servicerequest/10085103",
            "value": "00001"
          }
        ],
        "status": "active",
        "intent": "original-order",
        "priority": "routine",
        "category": [
          {
            "coding": [
              {
                "system": "http://snomed.info/sct",
                "code": "108252007",
                "display": "Laboratory procedure"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "2093-3",
              "display": "Cholesterol [Mass/volume] in Serum or Plasma"
            }
          ],
          "text": "Kolesterol Total"
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce",
          "display": "Permintaan Pemeriksaan Kolesterol Total Jum'at, 26 Mei  2023 pukul 09:30 WIB"
        },
        "occurrenceDateTime": "2023-05-28T16:30:00+00:00",
        "authoredOn": "2023-05-27T14:00:00+00:00",
        "requester": {
          "reference": "Practitioner/N10000001",
          "display": "Dokter Bronsig"
        },
        "performer": [
          {
            "reference": "Practitioner/N10000005",
            "display": "Fatma"
          }
        ],
        "reasonCode": [
          {
            "text": "Periksa Kolesterol Darah untuk Pelayanan Rawat Inap Pasien a.n Diana Smith"
          }
        ],
        "reasonReference": [
          {
            "Reference": "Condition/30041e06-7cec-45b4-a995-4bae0377ab66"
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "ServiceRequest"
      }
    },
    {
      "fullUrl": "urn:uuid:720dc040-578d-45d3-8868-85c0fdfe6115",
      "resource": {
        "resourceType": "Specimen",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/specimen/10085103",
            "value": "00001ABC",
            "assigner": {
              "reference": "Organization/10085103"
            }
          }
        ],
        "status": "available",
        "type": {
          "coding": [
            {
              "system": "http://snomed.info/sct",
              "code": "119297000",
              "display": "Blood specimen"
            }
          ]
        },
        "condition": [
          {
            "text": "Kondisi Spesimen Baik"
          }
        ],
        "collection": {
          "method": {
            "coding": [
              {
                "system": "http://snomed.info/sct",
                "code": "82078001",
                "display": "Collection of blood specimen for laboratory"
              }
            ]
          },
          "collectedDateTime": "2023-05-27T15:15:00+00:00",
          "quantity": {
            "value": 6,
            "unit": "mL"
          },
          "collector": {
            "reference": "Practitioner/N10000001",
            "display": "Dokter Bronsig"
          },
          "fastingStatusCodeableConcept": {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/v2-0916",
                "code": "F",
                "display": "Patient was fasting prior to the procedure."
              }
            ]
          }
        },
        "processing": [
          {
            "timeDateTime": "2023-05-28T16:30:00+00:00"
          }
        ],
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "request": [
          {
            "reference": "ServiceRequest/6220df5a-611a-4a0e-8545-89c3bd65db06"
          }
        ],
        "receivedTime": "2023-05-28T15:25:00+00:00",
        "extension": [
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedTime",
            "valueDateTime": "2023-05-27T15:00:00+00:00"
          },
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedPerson",
            "valueContactDetail": {
              "name": "Gojek",
              "telecom": [
                {
                  "system": "phone",
                  "value": "123-456-7890"
                }
              ]
            }
          },
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/ReceivedPerson",
            "valueReference": {
              "reference": "Practitioner/10006926841",
              "display": "Dr. John Doe"
            }
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Specimen"
      }
    },
    {
      "fullUrl": "urn:uuid:37139e6b-f6bb-42bc-b28e-cc36aac186f7",
      "resource": {
        "resourceType": "Observation",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/observation/10085103",
            "value": "O111111"
          }
        ],
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                "code": "laboratory",
                "display": "Laboratory"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "2093-3",
              "display": "Cholesterol [Mass/volume] in Serum or Plasma"
            }
          ]
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "effectiveDateTime": "2023-05-29T22:30:10+00:00",
        "issued": "2023-05-29T22:30:10+00:00",
        "performer": [
          {
            "reference": "Practitioner/N10000001"
          },
          {
            "reference": "Organization/10085103"
          }
        ],
        "specimen": {
          "reference": "urn:uuid:720dc004-578d-45d3-8868-85c0fcfe6115"
        },
        "basedOn": [
          {
            "reference": "urn:uuid:196e3c3a-6673-4ae0-acd9-1f03a95d7a32"
          }
        ],
        "valueQuantity": {
          "value": 240,
          "unit": "mg/dL",
          "system": "http://unitsofmeasure.org",
          "code": "mg/dL"
        },
        "interpretation": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                "code": "H",
                "display": "High"
              }
            ]
          }
        ],
        "referenceRange": [
          {
            "high": {
              "value": 200,
              "unit": "mg/dL",
              "system": "http://unitsofmeasure.org",
              "code": "mg/dL"
            },
            "text": "Normal"
          },
          {
            "low": {
              "value": 201,
              "unit": "mg/dL",
              "system": "http://unitsofmeasure.org",
              "code": "mg/dL"
            },
            "high": {
              "value": 239,
              "unit": "mg/dL",
              "system": "http://unitsofmeasure.org",
              "code": "mg/dL"
            },
            "text": "Borderline high"
          },
          {
            "low": {
              "value": 240,
              "unit": "mg/dL",
              "system": "http://unitsofmeasure.org",
              "code": "mg/dL"
            },
            "text": "High"
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Observation"
      }
    },
    {
      "fullUrl": "urn:uuid:8169f582-5f2e-4fa2-b594-9c59486ba9e1",
      "resource": {
        "resourceType": "DiagnosticReport",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/diagnostic/10085103/lab",
            "use": "official",
            "value": "52343421-B"
          }
        ],
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                "code": "CH",
                "display": "Chemistry"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "2093-3",
              "display": "Cholesterol [Mass/volume] in Serum or Plasma"
            }
          ]
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "effectiveDateTime": "2023-05-29T22:30:10+00:00",
        "issued": "2023-05-30T03:30:00+00:00",
        "performer": [
          {
            "reference": "Practitioner/N10000001"
          },
          {
            "reference": "Organization/10085103"
          }
        ],
        "result": [
          {
            "reference": "Observation/86825f8b-b695-42c3-a0bd-5ec43989e97e"
          }
        ],
        "specimen": [
          {
            "reference": "Specimen/a6244a41-342d-4023-8db3-414875697cd8"
          }
        ],
        "basedOn": [
          {
            "reference": "ServiceRequest/6220df5a-611a-4a0e-8545-89c3bd65db06"
          }
        ],
        "conclusionCode": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                "code": "H",
                "display": "High"
              }
            ]
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "DiagnosticReport"
      }
    },
    {
      "fullUrl": "urn:uuid:1963ce3a-6673-4ae0-acd9-1f03a95d7a32",
      "resource": {
        "resourceType": "ServiceRequest",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/servicerequest/10085103",
            "value": "00001A"
          }
        ],
        "status": "active",
        "intent": "original-order",
        "priority": "routine",
        "category": [
          {
            "coding": [
              {
                "system": "http://snomed.info/sct",
                "code": "108252007",
                "display": "Laboratory procedure"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "2161-8",
              "display": "Creatinine [Mass/volume] in Urine"
            }
          ],
          "text": "Kreatinin dalam Urin"
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce",
          "display": "Permintaan Kreatinin dalam Urin Sabtu, 28 Mei 2023 pukul 09:30 WIB"
        },
        "occurrenceDateTime": "2023-05-30T16:30:00+00:00",
        "authoredOn": "2023-05-29T14:00:00+00:00",
        "requester": {
          "reference": "Practitioner/N10000001",
          "display": "Dokter Bronsig"
        },
        "performer": [
          {
            "reference": "Practitioner/N10000005",
            "display": "Fatma"
          }
        ],
        "reasonCode": [
          {
            "text": "Periksa Kreatinin untuk Pelayanan Rawat Inap Pasien a.n Diana Smith"
          }
        ],
        "reasonReference": [
          {
            "Reference": "Condition/30041e06-7cec-45b4-a995-4bae0377ab66"
          }
        ],
        "note": [
          {
            "text": "Pasien tidak berpuasa terlebih dahulu"
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "ServiceRequest"
      }
    },
    {
      "fullUrl": "urn:uuid:720dc004-857d-45d3-8868-85c0fcfe6115",
      "resource": {
        "resourceType": "Specimen",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/specimen/10085103",
            "value": "00001ABC",
            "assigner": {
              "reference": "Organization/10085103"
            }
          }
        ],
        "status": "available",
        "type": {
          "coding": [
            {
              "system": "http://snomed.info/sct",
              "code": "122575003",
              "display": "Urine specimen"
            }
          ]
        },
        "condition": [
          {
            "text": "Kondisi Spesimen Baik"
          }
        ],
        "collection": {
          "method": {
            "coding": [
              {
                "system": "http://snomed.info/sct",
                "code": "57617002",
                "display": "Urine specimen collection"
              }
            ]
          },
          "collectedDateTime": "2023-05-29T15:15:00+00:00",
          "quantity": {
            "value": 30,
            "unit": "mL"
          },
          "collector": {
            "reference": "Practitioner/N10000001",
            "display": "Dokter Bronsig"
          },
          "fastingStatusCodeableConcept": {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/v2-0916",
                "code": "NF",
                "display": "The patient indicated they did not fast prior to the procedure."
              }
            ]
          }
        },
        "processing": [
          {
            "timeDateTime": "2023-05-30T16:30:00+00:00"
          }
        ],
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "request": [
          {
            "reference": "ServiceRequest/6220df5a-611a-4a0e-8545-89c3bd65db06"
          }
        ],
        "receivedTime": "2023-05-30T15:25:00+00:00",
        "extension": [
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedTime",
            "valueDateTime": "2023-05-29T15:00:00+00:00"
          },
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedPerson",
            "valueContactDetail": {
              "name": "Ners A",
              "telecom": [
                {
                  "system": "phone",
                  "value": "123-456-7890"
                }
              ]
            }
          },
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/ReceivedPerson",
            "valueReference": {
              "reference": "Practitioner/10006926841",
              "display": "Dr. John Doe"
            }
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Specimen"
      }
    },
    {
      "fullUrl": "urn:uuid:37139eb6-f6bb-42bc-b28e-cc36aac186f7",
      "resource": {
        "resourceType": "Observation",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/observation/10085103",
            "value": "O11111A"
          }
        ],
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                "code": "laboratory",
                "display": "Laboratory"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "2161-8",
              "display": "Creatinine [Mass/volume] in Urine"
            }
          ]
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "effectiveDateTime": "2023-05-30T22:30:10+00:00",
        "issued": "2023-05-30T22:30:10+00:00",
        "performer": [
          {
            "reference": "Practitioner/N10000001"
          },
          {
            "reference": "Organization/10085103"
          }
        ],
        "specimen": {
          "reference": "Specimen/9bbf1b01-1426-49fa-a48a-11f80e05dfdc"
        },
        "basedOn": [
          {
            "reference": "ServiceRequest/3cb0a48f-9a20-48d2-b881-9b88e8a75286"
          }
        ],
        "valueQuantity": {
          "value": 1.5,
          "unit": "mg/dL",
          "system": "http://unitsofmeasure.org",
          "code": "mg/dL"
        },
        "interpretation": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                "code": "H",
                "display": "High"
              }
            ]
          }
        ],
        "referenceRange": [
          {
            "high": {
              "value": 0.7,
              "unit": "mg/dL",
              "system": "http://unitsofmeasure.org",
              "code": "mg/dL"
            },
            "text": "Normal"
          },
          {
            "low": {
              "value": 0.8,
              "unit": "mg/dL",
              "system": "http://unitsofmeasure.org",
              "code": "mg/dL"
            },
            "high": {
              "value": 1.2,
              "unit": "mg/dL",
              "system": "http://unitsofmeasure.org",
              "code": "mg/dL"
            },
            "text": "Borderline high"
          },
          {
            "low": {
              "value": 1.5,
              "unit": "mg/dL",
              "system": "http://unitsofmeasure.org",
              "code": "mg/dL"
            },
            "text": "High"
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Observation"
      }
    },
    {
      "fullUrl": "urn:uuid:816f9528-5f2e-4fa2-b594-9c59486ba9e1",
      "resource": {
        "resourceType": "DiagnosticReport",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/diagnostic/10085103/lab",
            "use": "official",
            "value": "52343421-C"
          }
        ],
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                "code": "CH",
                "display": "Chemistry"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "2161-8",
              "display": "Creatinine [Mass/volume] in Urine"
            }
          ]
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "effectiveDateTime": "2023-05-30T22:30:10+00:00",
        "issued": "2023-05-30T03:30:00+00:00",
        "performer": [
          {
            "reference": "Practitioner/N10000001"
          },
          {
            "reference": "Organization/10085103"
          }
        ],
        "result": [
          {
            "reference": "Observation/89661100-d823-45f5-8a42-1669a39c795b"
          }
        ],
        "specimen": [
          {
            "reference": "Specimen/9bbf1b01-1426-49fa-a48a-11f80e05dfdc"
          }
        ],
        "basedOn": [
          {
            "reference": "ServiceRequest/3cb0a48f-9a20-48d2-b881-9b88e8a75286"
          }
        ],
        "conclusionCode": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                "code": "H",
                "display": "High"
              }
            ]
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "DiagnosticReport"
      }
    },
    {
      "fullUrl": "urn:uuid:aeb3e0d2-aa5d-4b40-8ca1-4cdb50991ee9",
      "resource": {
        "resourceType": "Medication",
        "meta": {
          "profile": [
            "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
          ]
        },
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/medication/10085103",
            "use": "official",
            "value": "123456-A"
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://sys-ids.kemkes.go.id/kfa",
              "code": "93017701",
              "display": "VITAMIN B6 25 mg TABLET (Umum)"
            }
          ]
        },
        "status": "active",
        "manufacturer": {
          "reference": "Organization/900001"
        },
        "form": {
          "coding": [
            {
              "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
              "code": "BS066",
              "display": "Tablet"
            }
          ]
        },
        "extension": [
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
            "valueCodeableConcept": {
              "coding": [
                {
                  "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                  "code": "NC",
                  "display": "Non-compound"
                }
              ]
            }
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Medication"
      }
    },
    {
      "fullUrl": "urn:uuid:b95feccf-a74b-4dc4-8077-26127c171ff9",
      "resource": {
        "resourceType": "MedicationRequest",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/prescription/10085103",
            "use": "official",
            "value": "123456788"
          },
          {
            "system": "http://sys-ids.kemkes.go.id/prescription-item/10085103",
            "use": "official",
            "value": "123456788-1"
          }
        ],
        "status": "completed",
        "intent": "order",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                "code": "inpatient",
                "display": "Inpatient"
              }
            ]
          }
        ],
        "priority": "routine",
        "medicationReference": {
          "reference": "Medication/ce897d50-3829-44da-bb4d-7a24032510a6",
          "display": "VITAMIN B6 25 mg TABLET (Umum)"
        },
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "authoredOn": "2023-05-27T14:00:00+00:00",
        "requester": {
          "reference": "Practitioner/N10000001",
          "display": "Dokter Bronsig"
        },
        "dosageInstruction": [
          {
            "sequence": 1,
            "text": "1 tablet per hari",
            "additionalInstruction": [
              {
                "text": "1 tablet per hari"
              }
            ],
            "patientInstruction": "1 tablet per hari",
            "timing": {
              "repeat": {
                "frequency": 1,
                "period": 1,
                "periodUnit": "d"
              }
            },
            "route": {
              "coding": [
                {
                  "system": "http://www.whocc.no/atc",
                  "code": "O",
                  "display": "Oral"
                }
              ]
            },
            "doseAndRate": [
              {
                "type": {
                  "coding": [
                    {
                      "system": "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                      "code": "ordered",
                      "display": "Ordered"
                    }
                  ]
                },
                "doseQuantity": {
                  "value": 1,
                  "unit": "TAB",
                  "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                  "code": "TAB"
                }
              }
            ]
          }
        ],
        "dispenseRequest": {
          "dispenseInterval": {
            "value": 1,
            "unit": "days",
            "system": "http://unitsofmeasure.org",
            "code": "d"
          },
          "validityPeriod": {
            "start": "2022-12-25T14:00:00+00:00",
            "end": "2024-05-24T14:00:00+00:00"
          },
          "numberOfRepeatsAllowed": 0,
          "quantity": {
            "value": 1,
            "unit": "TAB",
            "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
            "code": "TAB"
          },
          "expectedSupplyDuration": {
            "value": 1,
            "unit": "days",
            "system": "http://unitsofmeasure.org",
            "code": "d"
          },
          "performer": {
            "reference": "Organization/10085103"
          }
        }
      },
      "request": {
        "method": "POST",
        "url": "MedicationRequest"
      }
    },
    {
      "fullUrl": "urn:uuid:aeb3d0e2-aad5-4b40-8ca1-4cdb50991ee9",
      "resource": {
        "resourceType": "Medication",
        "meta": {
          "profile": [
            "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
          ]
        },
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/medication/10085103",
            "use": "official",
            "value": "123456-B"
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://sys-ids.kemkes.go.id/kfa",
              "code": "93017701",
              "display": "VITAMIN B6 25 mg TABLET (Umum)"
            }
          ]
        },
        "status": "active",
        "manufacturer": {
          "reference": "Organization/900001"
        },
        "form": {
          "coding": [
            {
              "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
              "code": "BS066",
              "display": "Tablet"
            }
          ]
        },
        "extension": [
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
            "valueCodeableConcept": {
              "coding": [
                {
                  "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                  "code": "NC",
                  "display": "Non-compound"
                }
              ]
            }
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Medication"
      }
    },
    {
      "fullUrl": "urn:uuid:b95fcecf-a7b4-4dc4-8077-26127c171ff9",
      "resource": {
        "resourceType": "MedicationRequest",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/prescription/10085103",
            "use": "official",
            "value": "123456788"
          },
          {
            "system": "http://sys-ids.kemkes.go.id/prescription-item/10085103",
            "use": "official",
            "value": "123456788-1"
          }
        ],
        "status": "completed",
        "intent": "order",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                "code": "inpatient",
                "display": "Inpatient"
              }
            ]
          }
        ],
        "priority": "routine",
        "medicationReference": {
          "reference": "Medication/0d26babb-f667-4d40-8562-616269ce50ce",
          "display": "VITAMIN B6 25 mg TABLET (Umum)"
        },
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "authoredOn": "2023-05-28T14:00:00+00:00",
        "requester": {
          "reference": "Practitioner/N10000001",
          "display": "Dokter Bronsig"
        },
        "dosageInstruction": [
          {
            "sequence": 1,
            "text": "1 tablet per hari",
            "additionalInstruction": [
              {
                "text": "1 tablet per hari"
              }
            ],
            "patientInstruction": "1 tablet per hari",
            "timing": {
              "repeat": {
                "frequency": 1,
                "period": 1,
                "periodUnit": "d"
              }
            },
            "route": {
              "coding": [
                {
                  "system": "http://www.whocc.no/atc",
                  "code": "O",
                  "display": "Oral"
                }
              ]
            },
            "doseAndRate": [
              {
                "type": {
                  "coding": [
                    {
                      "system": "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                      "code": "ordered",
                      "display": "Ordered"
                    }
                  ]
                },
                "doseQuantity": {
                  "value": 1,
                  "unit": "TAB",
                  "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                  "code": "TAB"
                }
              }
            ]
          }
        ],
        "dispenseRequest": {
          "dispenseInterval": {
            "value": 1,
            "unit": "days",
            "system": "http://unitsofmeasure.org",
            "code": "d"
          },
          "validityPeriod": {
            "start": "2022-12-25T14:00:00+00:00",
            "end": "2024-05-24T14:00:00+00:00"
          },
          "numberOfRepeatsAllowed": 0,
          "quantity": {
            "value": 1,
            "unit": "TAB",
            "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
            "code": "TAB"
          },
          "expectedSupplyDuration": {
            "value": 1,
            "unit": "days",
            "system": "http://unitsofmeasure.org",
            "code": "d"
          },
          "performer": {
            "reference": "Organization/10085103"
          }
        }
      },
      "request": {
        "method": "POST",
        "url": "MedicationRequest"
      }
    },
    {
      "fullUrl": "urn:uuid:aeb30de2-aa5d-4b40-8ca1-4cdb50991ee9",
      "resource": {
        "resourceType": "Medication",
        "meta": {
          "profile": [
            "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
          ]
        },
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/medication/10085103",
            "use": "official",
            "value": "123456-C"
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://sys-ids.kemkes.go.id/kfa",
              "code": "93017701",
              "display": "VITAMIN B6 25 mg TABLET (Umum)"
            }
          ]
        },
        "status": "active",
        "manufacturer": {
          "reference": "Organization/900001"
        },
        "form": {
          "coding": [
            {
              "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
              "code": "BS066",
              "display": "Tablet"
            }
          ]
        },
        "batch": {
          "lotNumber": "1625042A",
          "expirationDate": "2025-07-29T00:00:00+00:00"
        },
        "extension": [
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
            "valueCodeableConcept": {
              "coding": [
                {
                  "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                  "code": "NC",
                  "display": "Non-compound"
                }
              ]
            }
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Medication"
      }
    },
    {
      "fullUrl": "urn:uuid:b95fcefc-a74b-4dc4-8077-26127c171ff9",
      "resource": {
        "resourceType": "MedicationRequest",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/prescription/10085103",
            "use": "official",
            "value": "123456788"
          },
          {
            "system": "http://sys-ids.kemkes.go.id/prescription-item/10085103",
            "use": "official",
            "value": "123456788-1"
          }
        ],
        "status": "completed",
        "intent": "order",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                "code": "inpatient",
                "display": "Inpatient"
              }
            ]
          }
        ],
        "priority": "routine",
        "medicationReference": {
          "reference": "Medication/33dfa1c6-73d3-4e2c-9761-8a9860211a10",
          "display": "VITAMIN B6 25 mg TABLET (Umum)"
        },
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "authoredOn": "2023-05-29T14:00:00+00:00",
        "requester": {
          "reference": "Practitioner/N10000001",
          "display": "Dokter Bronsig"
        },
        "dosageInstruction": [
          {
            "sequence": 1,
            "text": "1 tablet per hari",
            "additionalInstruction": [
              {
                "text": "1 tablet per hari"
              }
            ],
            "patientInstruction": "1 tablet per hari",
            "timing": {
              "repeat": {
                "frequency": 1,
                "period": 1,
                "periodUnit": "d"
              }
            },
            "route": {
              "coding": [
                {
                  "system": "http://www.whocc.no/atc",
                  "code": "O",
                  "display": "Oral"
                }
              ]
            },
            "doseAndRate": [
              {
                "type": {
                  "coding": [
                    {
                      "system": "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                      "code": "ordered",
                      "display": "Ordered"
                    }
                  ]
                },
                "doseQuantity": {
                  "value": 1,
                  "unit": "TAB",
                  "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                  "code": "TAB"
                }
              }
            ]
          }
        ],
        "dispenseRequest": {
          "dispenseInterval": {
            "value": 1,
            "unit": "days",
            "system": "http://unitsofmeasure.org",
            "code": "d"
          },
          "validityPeriod": {
            "start": "2022-12-25T14:00:00+00:00",
            "end": "2024-05-24T14:00:00+00:00"
          },
          "numberOfRepeatsAllowed": 0,
          "quantity": {
            "value": 1,
            "unit": "TAB",
            "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
            "code": "TAB"
          },
          "expectedSupplyDuration": {
            "value": 1,
            "unit": "days",
            "system": "http://unitsofmeasure.org",
            "code": "d"
          },
          "performer": {
            "reference": "Organization/10085103"
          }
        }
      },
      "request": {
        "method": "POST",
        "url": "MedicationRequest"
      }
    },
    {
      "fullUrl": "urn:uuid:68ddb082-2775-4545-a0f3-705b463fcd97",
      "resource": {
        "resourceType": "QuestionnaireResponse",
        "questionnaire": "https://fhir.kemkes.go.id/Questionnaire/Q0007",
        "status": "completed",
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "authored": "2023-05-28T10:00:00+07:00",
        "author": {
          "reference": "Practitioner/N10000001"
        },
        "source": {
          "reference": "Patient/100000030015"
        },
        "item": [
          {
            "linkId": "1",
            "text": "Persyaratan Administrasi",
            "item": [
              {
                "linkId": "1.1",
                "text": "Apakah nama, umur, jenis kelamin, berat badan dan tinggi badan pasien sudah sesuai?",
                "answer": [
                  {
                    "valueCoding": {
                      "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                      "code": "OV000052",
                      "display": "Sesuai"
                    }
                  }
                ]
              },
              {
                "linkId": "1.2",
                "text": "Apakah nama, nomor ijin, alamat dan paraf dokter sudah sesuai?",
                "answer": [
                  {
                    "valueCoding": {
                      "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                      "code": "OV000052",
                      "display": "Sesuai"
                    }
                  }
                ]
              },
              {
                "linkId": "1.3",
                "text": "Apakah tanggal resep sudah sesuai?",
                "answer": [
                  {
                    "valueCoding": {
                      "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                      "code": "OV000052",
                      "display": "Sesuai"
                    }
                  }
                ]
              },
              {
                "linkId": "1.4",
                "text": "Apakah ruangan/unit asal resep sudah sesuai?",
                "answer": [
                  {
                    "valueCoding": {
                      "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                      "code": "OV000052",
                      "display": "Sesuai"
                    }
                  }
                ]
              },
              {
                "linkId": "2",
                "text": "Persyaratan Farmasetik",
                "item": [
                  {
                    "linkId": "2.1",
                    "text": "Apakah nama obat, bentuk dan kekuatan sediaan sudah sesuai?",
                    "answer": [
                      {
                        "valueCoding": {
                          "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                          "code": "OV000052",
                          "display": "Sesuai"
                        }
                      }
                    ]
                  },
                  {
                    "linkId": "2.2",
                    "text": "Apakah dosis dan jumlah obat sudah sesuai?",
                    "answer": [
                      {
                        "valueCoding": {
                          "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                          "code": "OV000052",
                          "display": "Sesuai"
                        }
                      }
                    ]
                  },
                  {
                    "linkId": "2.3",
                    "text": "Apakah stabilitas obat sudah sesuai?",
                    "answer": [
                      {
                        "valueCoding": {
                          "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                          "code": "OV000052",
                          "display": "Sesuai"
                        }
                      }
                    ]
                  },
                  {
                    "linkId": "2.4",
                    "text": "Apakah aturan dan cara penggunaan obat sudah sesuai?",
                    "answer": [
                      {
                        "valueCoding": {
                          "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                          "code": "OV000052",
                          "display": "Sesuai"
                        }
                      }
                    ]
                  }
                ]
              },
              {
                "linkId": "3",
                "text": "Persyaratan Klinis",
                "item": [
                  {
                    "linkId": "3.1",
                    "text": "Apakah ketepatan indikasi, dosis, dan waktu penggunaan obat sudah sesuai?",
                    "answer": [
                      {
                        "valueCoding": {
                          "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                          "code": "OV000052",
                          "display": "Sesuai"
                        }
                      }
                    ]
                  },
                  {
                    "linkId": "3.2",
                    "text": "Apakah terdapat duplikasi pengobatan?",
                    "answer": [
                      {
                        "valueBoolean": false
                      }
                    ]
                  },
                  {
                    "linkId": "3.3",
                    "text": "Apakah terdapat alergi dan reaksi obat yang tidak dikehendaki (ROTD)?",
                    "answer": [
                      {
                        "valueBoolean": false
                      }
                    ]
                  },
                  {
                    "linkId": "3.4",
                    "text": "Apakah terdapat kontraindikasi pengobatan?",
                    "answer": [
                      {
                        "valueBoolean": false
                      }
                    ]
                  },
                  {
                    "linkId": "3.5",
                    "text": "Apakah terdapat dampak interaksi obat?",
                    "answer": [
                      {
                        "valueBoolean": false
                      }
                    ]
                  }
                ]
              }
            ]
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "QuestionnaireResponse"
      }
    },
    {
      "fullUrl": "urn:uuid:1e1102cd-a791-42e5-86fd-fbb061ddaac8",
      "resource": {
        "resourceType": "Medication",
        "meta": {
          "profile": [
            "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
          ]
        },
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/medication/10085103",
            "use": "official",
            "value": "123456-D"
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://sys-ids.kemkes.go.id/kfa",
              "code": "93017701",
              "display": "VITAMIN B6 25 mg TABLET (Umum)"
            }
          ]
        },
        "status": "active",
        "manufacturer": {
          "reference": "Organization/900001"
        },
        "form": {
          "coding": [
            {
              "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
              "code": "BS066",
              "display": "Tablet"
            }
          ]
        },
        "batch": {
          "lotNumber": "1625042A",
          "expirationDate": "2025-07-29T00:00:00+00:00"
        },
        "extension": [
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
            "valueCodeableConcept": {
              "coding": [
                {
                  "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                  "code": "NC",
                  "display": "Non-compound"
                }
              ]
            }
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Medication"
      }
    },
    {
      "fullUrl": "urn:uuid:703dabce-102a-4fb2-954c-aeb33b6a56be",
      "resource": {
        "resourceType": "MedicationDispense",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/prescription/10085103",
            "use": "official",
            "value": "123456789"
          },
          {
            "system": "http://sys-ids.kemkes.go.id/prescription-item/10085103",
            "use": "official",
            "value": "123456788-2"
          }
        ],
        "status": "completed",
        "category": {
          "coding": [
            {
              "system": "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
              "code": "inpatient",
              "display": "Inpatient"
            }
          ]
        },
        "medicationReference": {
          "reference": "Medication/76d67b9f-2831-4000-b974-4e32bb1b9a04",
          "display": "VITAMIN B6 25 mg TABLET (Umum)"
        },
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "context": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "performer": [
          {
            "actor": {
              "reference": "Practitioner/N10000001",
              "display": "Dokter Bronsig"
            }
          }
        ],
        "location": {
          "reference": "Location/b29038d4-9ef0-4eb3-a2e9-3c02df668b07",
          "display": "Bed 2, Ruang 210, Bangsal Rawat Inap Kelas 1, Layanan Penyakit Dalam, Lantai 2, Gedung Utama"
        },
        "authorizingPrescription": [
          {
            "reference": "MedicationRequest/b5d747e8-4b4f-43cc-be06-6d207cacc332"
          }
        ],
        "quantity": {
          "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
          "code": "TAB",
          "value": 1
        },
        "daysSupply": {
          "value": 1,
          "unit": "Day",
          "system": "http://unitsofmeasure.org",
          "code": "d"
        },
        "whenPrepared": "2023-05-27T14:00:00+00:00",
        "whenHandedOver": "2023-05-27T14:30:00+00:00",
        "dosageInstruction": [
          {
            "sequence": 1,
            "text": "1 tablet per hari",
            "additionalInstruction": [
              {
                "text": "1 tablet per hari"
              }
            ],
            "patientInstruction": "1 tablet per hari",
            "timing": {
              "repeat": {
                "frequency": 1,
                "period": 1,
                "periodUnit": "d"
              }
            },
            "route": {
              "coding": [
                {
                  "system": "http://www.whocc.no/atc",
                  "code": "O",
                  "display": "Oral"
                }
              ]
            },
            "doseAndRate": [
              {
                "type": {
                  "coding": [
                    {
                      "system": "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                      "code": "ordered",
                      "display": "Ordered"
                    }
                  ]
                },
                "doseQuantity": {
                  "value": 1,
                  "unit": "TAB",
                  "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                  "code": "TAB"
                }
              }
            ]
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "MedicationDispense"
      }
    },
    {
      "fullUrl": "urn:uuid:11e120dc-a791-42e5-86fd-fbb061ddaca8",
      "resource": {
        "resourceType": "Medication",
        "meta": {
          "profile": [
            "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
          ]
        },
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/medication/10085103",
            "use": "official",
            "value": "123456-E"
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://sys-ids.kemkes.go.id/kfa",
              "code": "93017701",
              "display": "VITAMIN B6 25 mg TABLET (Umum)"
            }
          ]
        },
        "status": "active",
        "manufacturer": {
          "reference": "Organization/900001"
        },
        "form": {
          "coding": [
            {
              "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
              "code": "BS066",
              "display": "Tablet"
            }
          ]
        },
        "batch": {
          "lotNumber": "1625042A",
          "expirationDate": "2025-07-29T00:00:00+00:00"
        },
        "extension": [
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
            "valueCodeableConcept": {
              "coding": [
                {
                  "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                  "code": "NC",
                  "display": "Non-compound"
                }
              ]
            }
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Medication"
      }
    },
    {
      "fullUrl": "urn:uuid:703dabec-10a2-2f4b-954c-aeb33b6a56be",
      "resource": {
        "resourceType": "MedicationDispense",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/prescription/10085103",
            "use": "official",
            "value": "123456789"
          },
          {
            "system": "http://sys-ids.kemkes.go.id/prescription-item/10085103",
            "use": "official",
            "value": "123456788-2"
          }
        ],
        "status": "completed",
        "category": {
          "coding": [
            {
              "system": "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
              "code": "inpatient",
              "display": "Inpatient"
            }
          ]
        },
        "medicationReference": {
          "reference": "Medication/db07896f-ede8-48fa-9bf0-e99941a6a375",
          "display": "VITAMIN B6 25 mg TABLET (Umum)"
        },
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "context": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "performer": [
          {
            "actor": {
              "reference": "Practitioner/N10000001",
              "display": "Dokter Bronsig"
            }
          }
        ],
        "location": {
          "reference": "Location/b29038d4-9ef0-4eb3-a2e9-3c02df668b07",
          "display": "Bed 2, Ruang 210, Bangsal Rawat Inap Kelas 1, Layanan Penyakit Dalam, Lantai 2, Gedung Utama"
        },
        "authorizingPrescription": [
          {
            "reference": "MedicationRequest/78c5d8d1-e3bf-4ff1-bf3c-6aa564336ee7"
          }
        ],
        "quantity": {
          "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
          "code": "TAB",
          "value": 1
        },
        "daysSupply": {
          "value": 1,
          "unit": "Day",
          "system": "http://unitsofmeasure.org",
          "code": "d"
        },
        "whenPrepared": "2023-05-29T14:00:00+00:00",
        "whenHandedOver": "2023-05-29T14:30:00+00:00",
        "dosageInstruction": [
          {
            "sequence": 1,
            "text": "1 tablet per hari",
            "additionalInstruction": [
              {
                "text": "1 tablet per hari"
              }
            ],
            "patientInstruction": "1 tablet per hari",
            "timing": {
              "repeat": {
                "frequency": 1,
                "period": 1,
                "periodUnit": "d"
              }
            },
            "route": {
              "coding": [
                {
                  "system": "http://www.whocc.no/atc",
                  "code": "O",
                  "display": "Oral"
                }
              ]
            },
            "doseAndRate": [
              {
                "type": {
                  "coding": [
                    {
                      "system": "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                      "code": "ordered",
                      "display": "Ordered"
                    }
                  ]
                },
                "doseQuantity": {
                  "value": 1,
                  "unit": "TAB",
                  "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                  "code": "TAB"
                }
              }
            ]
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "MedicationDispense"
      }
    },
    {
      "fullUrl": "urn:uuid:1e112d0c-a917-42e5-86fd-fbb061ddaca8",
      "resource": {
        "resourceType": "Medication",
        "meta": {
          "profile": [
            "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
          ]
        },
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/medication/10085103",
            "use": "official",
            "value": "123456-F"
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://sys-ids.kemkes.go.id/kfa",
              "code": "93017701",
              "display": "VITAMIN B6 25 mg TABLET (Umum)"
            }
          ]
        },
        "status": "active",
        "manufacturer": {
          "reference": "Organization/900001"
        },
        "form": {
          "coding": [
            {
              "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
              "code": "BS066",
              "display": "Tablet"
            }
          ]
        },
        "batch": {
          "lotNumber": "1625042A",
          "expirationDate": "2025-07-29T00:00:00+00:00"
        },
        "extension": [
          {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
            "valueCodeableConcept": {
              "coding": [
                {
                  "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                  "code": "NC",
                  "display": "Non-compound"
                }
              ]
            }
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Medication"
      }
    },
    {
      "fullUrl": "urn:uuid:703dabce-120a-4f2b-954c-aeb33b6a56be",
      "resource": {
        "resourceType": "MedicationDispense",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/prescription/10085103",
            "use": "official",
            "value": "123456789"
          },
          {
            "system": "http://sys-ids.kemkes.go.id/prescription-item/10085103",
            "use": "official",
            "value": "123456788-2"
          }
        ],
        "status": "completed",
        "category": {
          "coding": [
            {
              "system": "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
              "code": "inpatient",
              "display": "Inpatient"
            }
          ]
        },
        "medicationReference": {
          "reference": "Medication/d25201d0-d6de-483a-98f3-6dc43fc3efc4",
          "display": "VITAMIN B6 25 mg TABLET (Umum)"
        },
        "subject": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "context": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "performer": [
          {
            "actor": {
              "reference": "Practitioner/N10000001",
              "display": "Dokter Bronsig"
            }
          }
        ],
        "location": {
          "reference": "Location/b29038d4-9ef0-4eb3-a2e9-3c02df668b07",
          "display": "Bed 2, Ruang 210, Bangsal Rawat Inap Kelas 1, Layanan Penyakit Dalam, Lantai 2, Gedung Utama"
        },
        "authorizingPrescription": [
          {
            "reference": "MedicationRequest/c6a79ce0-b2da-4b00-8f41-60187b839db3"
          }
        ],
        "quantity": {
          "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
          "code": "TAB",
          "value": 1
        },
        "daysSupply": {
          "value": 1,
          "unit": "Day",
          "system": "http://unitsofmeasure.org",
          "code": "d"
        },
        "whenPrepared": "2023-05-30T14:00:00+00:00",
        "whenHandedOver": "2023-05-30T14:30:00+00:00",
        "dosageInstruction": [
          {
            "sequence": 1,
            "text": "1 tablet per hari",
            "additionalInstruction": [
              {
                "text": "1 tablet per hari"
              }
            ],
            "patientInstruction": "1 tablet per hari",
            "timing": {
              "repeat": {
                "frequency": 1,
                "period": 1,
                "periodUnit": "d"
              }
            },
            "route": {
              "coding": [
                {
                  "system": "http://www.whocc.no/atc",
                  "code": "O",
                  "display": "Oral"
                }
              ]
            },
            "doseAndRate": [
              {
                "type": {
                  "coding": [
                    {
                      "system": "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                      "code": "ordered",
                      "display": "Ordered"
                    }
                  ]
                },
                "doseQuantity": {
                  "value": 1,
                  "unit": "TAB",
                  "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                  "code": "TAB"
                }
              }
            ]
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "MedicationDispense"
      }
    },
    {
      "fullUrl": "urn:uuid:39ad4a1c-dc1b-4a71-9c59-778b6c1503d3",
      "resource": {
        "resourceType": "Observation",
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                "code": "survey",
                "display": "Survey"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "82810-3",
              "display": "Pregnancy status"
            }
          ]
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "performer": [
          {
            "reference": "Practitioner/N10000001"
          }
        ],
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce",
          "display": "Kunjungan Diana Smith di hari Kamis, 26 Mei 2023"
        },
        "effectiveDateTime": "2023-05-26T08:00:00+00:00",
        "issued": "2023-05-26T08:00:00+00:00",
        "valueCodeableConcept": {
          "coding": [
            {
              "system": "http://snomed.info/sct",
              "code": "60001007",
              "display": "Not pregnant"
            }
          ]
        }
      },
      "request": {
        "method": "POST",
        "url": "Observation"
      }
    },
    {
      "fullUrl": "urn:uuid:3feb620d-8688-4349-b5bc-ff25277e0021",
      "resource": {
        "resourceType": "AllergyIntolerance",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/allergy/10085103",
            "use": "official",
            "value": "10085103"
          }
        ],
        "clinicalStatus": {
          "coding": [
            {
              "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical",
              "code": "active",
              "display": "Active"
            }
          ]
        },
        "verificationStatus": {
          "coding": [
            {
              "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-verification",
              "code": "confirmed",
              "display": "Confirmed"
            }
          ]
        },
        "category": [
          "medication"
        ],
        "code": {
          "coding": [
            {
              "system": "http://sys-ids.kemkes.go.id/kfa",
              "code": "91000299",
              "display": "Aspirin"
            }
          ],
          "text": "Alergi Aspirin"
        },
        "patient": {
          "reference": "Patient/100000030015",
          "display": "Diana Smith"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce",
          "display": "Kunjungan Diana Smith di hari Kamis, 25 Mei 2023"
        },
        "recordedDate": "2023-05-27T08:00:00+00:00",
        "recorder": {
          "reference": "Practitioner/N10000001"
        }
      },
      "request": {
        "method": "POST",
        "url": "AllergyIntolerance"
      }
    },
    {
      "fullUrl": "urn:uuid:196e3c3a-6763-4ae0-adc9-1f03a95d7a32",
      "resource": {
        "resourceType": "ServiceRequest",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/servicerequest/10085103",
            "value": "00001B"
          },
          {
            "use": "usual",
            "type": {
              "coding": [
                {
                  "system": "http://terminology.hl7.org/CodeSystem/v2-0203",
                  "code": "ACSN"
                }
              ]
            },
            "system": "http://sys-ids.kemkes.go.id/acsn/10085103",
            "value": "21120054"
          }
        ],
        "status": "active",
        "intent": "original-order",
        "priority": "routine",
        "category": [
          {
            "coding": [
              {
                "system": "http://snomed.info/sct",
                "code": "363679005",
                "display": "Imaging"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "46322-4",
              "display": "CT Kidney W contrast IV"
            }
          ],
          "text": "Pemeriksaan CT Scan Ginjal"
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "occurrenceDateTime": "2023-05-28T16:30:27+00:00",
        "authoredOn": "2023-05-28T19:30:27+00:00",
        "requester": {
          "reference": "Practitioner/N10000001",
          "display": "Dokter Bronsig"
        },
        "performer": [
          {
            "reference": "Practitioner/N10000005",
            "display": "Fatma"
          }
        ],
        "bodySite": [
          {
            "coding": [
              {
                "system": "http://snomed.info/sct",
                "code": "64033007",
                "display": "Kidney structure"
              }
            ]
          }
        ],
        "reasonCode": [
          {
            "text": "Pemeriksaan CT Scan Ginjal untuk Pelayanan Rawat Inap Pasien a.n Diana Smith"
          }
        ],
        "reasonReference": [
          {
            "Reference": "Condition/30041e06-7cec-45b4-a995-4bae0377ab66"
          }
        ],
        "note": [
          {
            "text": "Pemeriksaan CT Scan Ginjal"
          }
        ],
        "supportingInfo": [
          {
            "reference": "Observation/0f75c423-7fd4-41ea-8922-a2424838cef6"
          },
          {
            "reference": "AllergyIntolerance/a717ee01-8426-4bc7-a1d4-ae2db0d2a7c9"
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "ServiceRequest"
      }
    },
    {
      "fullUrl": "urn:uuid:37193eb6-f6bb-42cb-b28e-cc36aac186f7",
      "resource": {
        "resourceType": "Observation",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/observation/10085103",
            "value": "O111111B"
          }
        ],
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                "code": "imaging",
                "display": "Imaging"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "46322-4",
              "display": "CT Kidney W contrast IV"
            }
          ]
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "effectiveDateTime": "2023-05-28T23:30:10+00:00",
        "issued": "2023-05-28T23:30:10+00:00",
        "performer": [
          {
            "reference": "Practitioner/N10000001"
          },
          {
            "reference": "Organization/10085103"
          }
        ],
        "basedOn": [
          {
            "reference": "ServiceRequest/09e32d32-9c72-442d-a557-a9e3887d63a0"
          }
        ],
        "bodySite": {
          "coding": [
            {
              "system": "http://snomed.info/sct",
              "code": "64033007",
              "display": "Kidney structure"
            }
          ]
        },
        "derivedFrom": [
          {
            "reference": "ImagingStudy/c4f3bfe3-91cd-40c4-b986-000c2150f051"
          }
        ],
        "valueString": "Ditemukan kelainan dalam CT Kidney"
      },
      "request": {
        "method": "POST",
        "url": "Observation"
      }
    },
    {
      "fullUrl": "urn:uuid:816f9852-5f2e-4fa2-b594-9c59486ba9e1",
      "resource": {
        "resourceType": "DiagnosticReport",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/diagnostic/10085103/rad",
            "use": "official",
            "value": "52343421-A"
          }
        ],
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                "code": "RAD",
                "display": "Radiology"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "87847-0",
              "display": "CT Chest WO and CT angiogram Coronary arteries W contrast IV"
            }
          ]
        },
        "subject": {
          "reference": "Patient/100000030015"
        },
        "encounter": {
          "reference": "Encounter/5bc05f8a-fba4-4b1b-8a36-a31ba3b3cdce"
        },
        "effectiveDateTime": "2023-05-27T01:00:00+00:00",
        "issued": "2023-05-28T15:00:00+00:00",
        "performer": [
          {
            "reference": "Practitioner/N10000001"
          },
          {
            "reference": "Organization/10085103"
          }
        ],
        "imagingStudy": [
          {
            "reference": "ImagingStudy/c4f3bfe3-91cd-40c4-b986-000c2150f051"
          }
        ],
        "result": [
          {
            "reference": "Observation/d825bb67-1357-425c-b1a9-0ed6e89a4339"
          }
        ],
        "basedOn": [
          {
            "reference": "ServiceRequest/09e32d32-9c72-442d-a557-a9e3887d63a0"
          }
        ],
        "conclusion": "Ditemukan Sumbatan pada bagian Saluran Kemih"
      },
      "request": {
        "method": "POST",
        "url": "DiagnosticReport"
      }
    }
  ]
}


';
?>
