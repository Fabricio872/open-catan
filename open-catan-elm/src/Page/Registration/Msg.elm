module Page.Registration.Msg exposing (DataMsg(..), Msg(..))

import Http


type Msg
    = Register
    | RegisterResponse (Result Http.Error String)
    | InsertedData DataMsg


type DataMsg
    = InsertedUsername String
