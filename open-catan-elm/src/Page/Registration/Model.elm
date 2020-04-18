module Page.Registration.Model exposing (Data, Model)


type alias Model =
    { data : Data
    , response : String
    }


type alias Data =
    { username : String
    , email : String
    , password : String
    }
