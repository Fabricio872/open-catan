module Main exposing (..)

import Browser
import Element
import Html exposing (Html)
import Page.Registration.Init
import Page.Registration.Model
import Page.Registration.Msg
import Page.Registration.Update
import Page.Registration.View



---- MODEL ----


type alias Model =
    { page : Page }


type Page
    = RegistrationPage Page.Registration.Model.Model


init : ( Model, Cmd Msg )
init =
    ( { page = RegistrationPage <| Tuple.first Page.Registration.Init.init }
    , Cmd.none
    )



---- UPDATE ----


type Msg
    = RegistrationMsg Page.Registration.Msg.Msg


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        RegistrationMsg subMsg ->
            let
                ( updatedPage, cmd ) =
                    case model.page of
                        RegistrationPage subModel ->
                            Page.Registration.Update.update subMsg subModel
                                |> Tuple.mapBoth RegistrationPage (Cmd.map RegistrationMsg)
            in
            ( { model | page = updatedPage }, cmd )



---- VIEW ----


view : Model -> Html Msg
view model =
    Element.layout [] <|
        case model.page of
            RegistrationPage subModel ->
                Page.Registration.View.view subModel
                    |> Element.map RegistrationMsg



---- PROGRAM ----


main : Program () Model Msg
main =
    Browser.element
        { view = view
        , init = \_ -> init
        , update = update
        , subscriptions = always Sub.none
        }
