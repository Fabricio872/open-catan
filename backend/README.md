### API Usage Table
| Entrypoint       | Method | Header                                                        | Body                                                             | Response                                                             |
|------------------|--------|---------------------------------------------------------------|------------------------------------------------------------------|----------------------------------------------------------------------|
| /api/login_check | POST   | Content-Type: application/json                                | {"username": "string", "password": "string" }                    | { "token": "string" }                                                |
| /register        | POST   | Content-Type: application/json                                | {"username": "string", "email": "string", "password": "string" } | { "status": 200, "success": "User <username> successfully created" } |
| /api             | GET    | Content-Type: application/json, Authorization: Bearer [token] |                                                                  | "you are logged in"                                                  |
