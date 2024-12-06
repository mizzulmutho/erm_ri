<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$json_data='{
  "resourceType": "Bundle",
  "type": "transaction",
  "entry": [
    {
      "fullUrl": "urn:uuid:'.$uuid1.'",
      "resource": {
        "resourceType": "Encounter",
        "identifier": [
          {
            "system": "http://sys-ids.kemkes.go.id/encounter/'.$organisation.'",
            "value": "'.$noreg.'"
          }
        ],
        "status": "finished",
        "class": {
          "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
          "code": "'.$jenis.'",
          "display": "'.$namajenis.'"
        },
        "subject": {
          "reference": "Patient/'.$ihsnumber.'",
          "display": "'.$namapasien.'"
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
              "reference": "Practitioner/'.$iddokter.'",
              "display": "'.$namadokter.'"
            },
            "period": {
              "start": "'.$tanggal.'",
              "end": "'.$tglkeluar.'"
            }
          },
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
              "reference": "Practitioner/'.$iddokter.'",
              "display": "'.$namadokter.'"
            },
            "period": {
              "start": "'.$tanggal.'",
              "end": "'.$tglkeluar.'"
            }
          }
        ],
        "period": {
          "start": "'.$tanggal.'",
          "end": "'.$tglkeluar.'"
        },
        "location": [
          {
            "location": {
              "reference": "Location/'.$location.'",
              "display": "'.$display_ruang.'"
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
              "reference": "Condition/urn:uuid:'.$uuid2.'",
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
              "reference": "Condition/urn:uuid:'.$uuid3.'",
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
              "start": "'.$tanggal.'",
              "end": "'.$tanggal.'"
            }
          },
          {
            "status": "finished",
            "period": {
              "start": "'.$tanggal.'",
              "end": "'.$tglkeluar.'"
            }
          }
        ],
        "hospitalization": {
          "dischargeDisposition": {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/discharge-disposition",
                "code": "home",
                "display": "Home"
              }
            ],
            "text": "Anjuran dokter untuk pulang dan kontrol kembali"
          }
        },
        "serviceProvider": {
          "reference": "Organization/'.$organisation.'"
        },
        "basedOn": [
          {
            "reference": "ServiceRequest/urn:uuid:'.$uuid4.'"
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Encounter"
      }
    },
    {
      "fullUrl": "urn:uuid:'.$uuid2.'",
      "resource": {
        "category": [
          {
            "coding": [
              {
                "code": "encounter-diagnosis",
                "display": "Encounter Diagnosis",
                "system": "http://terminology.hl7.org/CodeSystem/condition-category"
              }
            ]
          }
        ],
        "clinicalStatus": {
          "coding": [
            {
              "code": "active",
              "display": "Active",
              "system": "http://terminology.hl7.org/CodeSystem/condition-clinical"
            }
          ]
        },
        "code": {
          "coding": [
            {
              "system": "http://hl7.org/fhir/sid/icd-10",
              "code": "'.$kodeicd.'",
              "display": "'.$namadiagnosa.'"
            }
          ]
        },
        "encounter": {
          "reference": "urn:uuid:'.$uuid1.'"
        },
        "onsetDateTime": "'.$tanggal.'",
        "recordedDate": "'.$tanggal.'",
        "note": [
          {
            "text": "Pasien Sakit dg diagnosa :'.$namadiagnosa.'"
          }
        ],
        "resourceType": "Condition",
        "subject": {
          "display": "'.$namapasien.'",
          "reference": "Patient/'.$ihsnumber.'"
        }
      },
      "request": {
        "method": "POST",
        "url": "Condition"
      }
    },
    {
      "fullUrl": "urn:uuid:'.$uuid3.'",
      "resource": {
        "category": [
          {
            "coding": [
              {
                "code": "encounter-diagnosis",
                "display": "Encounter Diagnosis",
                "system": "http://terminology.hl7.org/CodeSystem/condition-category"
              }
            ]
          }
        ],
        "clinicalStatus": {
          "coding": [
            {
              "code": "active",
              "display": "Active",
              "system": "http://terminology.hl7.org/CodeSystem/condition-clinical"
            }
          ]
        },
        "code": {
          "coding": [
            {
              "code": "'.$kodeicd.'",
              "display": "'.$namadiagnosa.'",
              "system": "http://hl7.org/fhir/sid/icd-10"
            }
          ]
        },	
        "encounter": {
          "display": "Kunjungan '.$namapasien.' di tanggal '.$tglmasuk.'",
          "reference": "urn:uuid:'.$uuid1.'"
        },
        "onsetDateTime": "'.$tanggal.'",
        "recordedDate": "'.$tanggal.'",
        "resourceType": "Condition",
        "subject": {
          "reference": "Patient/'.$ihsnumber.'",
          "display": "'.$namapasien.'"
        }
      },
      "request": {
        "method": "POST",
        "url": "Condition"
      }
    },
    {
      "fullUrl": "urn:uuid:'.$uuid5.'",
      "resource": {
        "resourceType": "Procedure",
        "status": "completed",
        "category": {
          "coding": [
            {
              "system": "http://snomed.info/sct",
              "code": "103693007",
              "display": "Diagnostic procedure"
            }
          ],
          "text": "Diagnostic procedure"
        },
        "code": {
          "coding": [
            {
              "system": "http://hl7.org/fhir/sid/icd-9-cm",
              "code": "'.$kodeicd9.'",
              "display": "'.$namaprocedure.'"
            }
          ]
        },
        "subject": {
          "reference": "Patient/'.$ihsnumber.'",
          "display": "'.$namapasien.'"
        },
        "encounter": {
          "reference": "urn:uuid:'.$uuid1.'"
        },
        "performedPeriod": {
          "start": "'.$tanggal.'",
          "end": "'.$tglkeluar.'"
        },
        "performer": [
          {
            "actor": {
              "reference": "Practitioner/'.$iddokter.'",
              "display": "'.$namadokter.'"
            }
          }
        ]
      },
      "request": {
        "method": "POST",
        "url": "Procedure"
      }
    },
    {
      "fullUrl": "urn:uuid:'.$uuid6.'",
      "resource": {
        "resourceType": "Observation",
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                "code": "exam",
                "display": "Exam"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "67775-7",
              "display": "Level of responsiveness"
            }
          ]
        },
        "subject": {
          "reference": "Patient/'.$ihsnumber.'",
          "display": "'.$namapasien.'"
        },
        "encounter": {
          "reference": "urn:uuid:'.$uuid1.'"
        },
        "effectiveDateTime": "'.$tglperiksa.'",
        "issued": "'.$tanggal.'",
        "performer": [
          {
            "reference": "Practitioner/'.$iddokter.'"
          }
        ],
        "valueCodeableConcept": {
          "coding": [
            {
              "system": "http://snomed.info/sct",
              "code": "248234008",
              "display": "Mentally alert"
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
      "fullUrl": "urn:uuid:'.$uuid7.'",
      "resource": {
        "resourceType": "Observation",
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                "code": "vital-signs",
                "display": "Vital Signs"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "8867-4",
              "display": "Heart rate"
            }
          ]
        },
        "subject": {
          "reference": "Patient/'.$ihsnumber.'",
          "display": "'.$namapasien.'"
        },
        "encounter": {
          "reference": "urn:uuid:'.$uuid1.'"
        },
        "effectiveDateTime": "'.$tglperiksa.'",
        "issued": "'.$tanggal.'",
        "performer": [
          {
            "reference": "Practitioner/'.$iddokter.'"
          }
        ],
        "valueQuantity": {
          "value": 80,
          "unit": "beats/minute",
          "system": "http://unitsofmeasure.org",
          "code": "/min"
        }
      },
      "request": {
        "method": "POST",
        "url": "Observation"
      }
    },
    {
      "fullUrl": "urn:uuid:'.$uuid8.'",
      "resource": {
        "resourceType": "Observation",
        "status": "final",
        "category": [
          {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                "code": "exam",
                "display": "Exam"
              }
            ]
          }
        ],
        "code": {
          "coding": [
            {
              "system": "http://loinc.org",
              "code": "10199-8",
              "display": "Physical findings of Head Narrative"
            }
          ]
        },
        "subject": {
          "reference": "Patient/'.$ihsnumber.'",
          "display": "'.$namapasien.'"
        },
        "encounter": {
          "reference": "urn:uuid:'.$uuid1.'"
        },
        "effectiveDateTime": "'.$tglperiksa.'",
        "issued": "'.$tanggal.'",
        "performer": [
          {
            "reference": "Practitioner/'.$iddokter.'"
          }
        ],
        "valueString": "Bentuk kepala simetris"
      },
      "request": {
        "method": "POST",
        "url": "Observation"
      }
    }
  ]
}
';
?>
