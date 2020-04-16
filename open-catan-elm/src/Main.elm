module Main exposing (..)

import Browser
import Html exposing (Html, div, h1, img, text)
import Html.Attributes as Attributes exposing (src)
import Html.Events as Events
import Http
import Json.Decode as Decode
import Json.Encode as Encode



---- MODEL ----


type alias Model =
    { data : Data
    , response : String
    }


type alias Data =
    { username : String
    , email : String
    , password : String
    }


init : ( Model, Cmd Msg )
init =
    ( { data = Data "name" "email" "password"
      , response = ""
      }
    , Cmd.none
    )



---- UPDATE ----


type Msg
    = Register
    | RegisterResponse (Result Http.Error String)


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        Register ->
            ( model, registerCmd model.data )

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


registerCmd : Data -> Cmd Msg
registerCmd data =
    let
        url =
            "http://localhost:8000" ++ "/register"
    in
    Http.request
        { method = "POST"
        , headers = [ Http.header "Content-Type" "application/json" ]
        , url = url
        , body = Http.jsonBody <| dataEncoder data
        , expect = Http.expectJson RegisterResponse responseDecoder
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


responseDecoder : Decode.Decoder String
responseDecoder =
    Decode.field "success" Decode.string



---- VIEW ----


view : Model -> Html Msg
view model =
    div []
        [ img [ src "/logo.svg" ] []
        , Html.button [ Events.onClick Register ] [ Html.text "Send register" ]
        , Html.text model.response
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
