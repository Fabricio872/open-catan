module Page.Registration.Init exposing (init)

import Page.Registration.Model exposing (Data, Model)
import Page.Registration.Msg exposing (Msg)


init : ( Model, Cmd Msg )
init =
    ( { data = Data "" "" ""
      , response = ""
      }
    , Cmd.none
    )
