module Page.Registration.Http exposing (registerCmd)

import Http
import Json.Decode as Decode
import Json.Encode as Encode
import Page.Registration.Model exposing (Data)
import Page.Registration.Msg exposing (Msg(..))


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
