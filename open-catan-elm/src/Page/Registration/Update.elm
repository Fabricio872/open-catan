module Page.Registration.Update exposing (update)

import Page.Registration.Http as Http
import Page.Registration.Model exposing (Data, Model)
import Page.Registration.Msg exposing (DataMsg(..), Msg(..))


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        Register ->
            ( model, Http.registerCmd model.data )

        RegisterResponse result ->
            case result of
                Err error ->
                    let
                        _ =
                            Debug.log "error" error
                    in
                    ( model, Cmd.none )

                Ok response ->
                    ( { model | response = response }, Cmd.none )

        InsertedData subMsg ->
            ( { model | data = updateData subMsg model.data }, Cmd.none )


updateData : DataMsg -> Data -> Data
updateData msg data =
    case msg of
        InsertedUsername username ->
            { data | username = username }
