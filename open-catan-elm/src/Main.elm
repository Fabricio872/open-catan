module Main exposing (..)

import Browser
import Html exposing (Html, div, h1, img, text)
import Html.Attributes as Attributes exposing (src)
import Html.Events as Events
import Http
import Json.Encode as Encode



---- MODEL ----


type alias Model =
    { data : Data }


type alias Data =
    { username : String
    , email : String
    , password : String
    }


init : ( Model, Cmd Msg )
init =
    ( { data = Data "name" "email" "password" }, Cmd.none )



---- UPDATE ----


type Msg
    = Register
    | RegisterResponse (Result Http.Error ())


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        Register ->
            ( model, registerCmd model.data )

        RegisterResponse _ ->
            ( model, Cmd.none )


registerCmd : Data -> Cmd Msg
registerCmd data =
    let
        url =
            "http://localhost:3000" ++ "/register"
    in
    Http.request
        { method = "POST"
        , headers = [ Http.header "Content-Type" "application/json" ]
        , url = url
        , body = Http.jsonBody <| dataEncoder data
        , expect = Http.expectWhatever RegisterResponse
        , timeout = Nothing
        , tracker = Nothing
        }


dataEncoder : Data -> Encode.Value
dataEncoder { username, email, password } =
    Encode.object
        [ ( "username", Encode.string username )
        , ( "email", Encode.string email )
        , ( "password", Encode.string password )
        ]



---- VIEW ----


view : Model -> Html Msg
view model =
    div []
        [ img [ src "/logo.svg" ] []
        , h1 []
            [ Html.button [ Events.onClick Register ] []
            ]
        ]



---- PROGRAM ----


main : Program () Model Msg
main =
    Browser.element
        { view = view
        , init = \_ -> init
        , update = update
        , subscriptions = always Sub.none
        }
