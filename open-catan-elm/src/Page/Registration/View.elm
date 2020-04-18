module Page.Registration.View exposing (view)

import Element exposing (Element)
import Element.Input as Input
import Page.Registration.Model exposing (Model)
import Page.Registration.Msg exposing (DataMsg(..), Msg(..))


view : Model -> Element Msg
view model =
    Element.column
        []
        [ Input.username []
            { onChange = InsertedData << InsertedUsername
            , text = "Username"
            , placeholder = Nothing
            , label = Input.labelAbove [] <| Element.text "USERNAME:"
            }
        , Element.text model.data.username
        , Input.button []
            { onPress = Just Register
            , label = Element.text "Send register"
            }
        , Element.text model.response
        ]
