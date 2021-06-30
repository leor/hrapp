import React from 'react';
import {Alert} from "react-bootstrap";
import {ErrorProps} from "../types";

const ErrorMessage = ({message}: ErrorProps) => (
    <Alert transition={false} variant="danger" show={!!message}>
        <Alert.Heading>Error!</Alert.Heading>
        <p>{message}</p>
    </Alert>
)

export default ErrorMessage
