import React, {useEffect, useState} from 'react'
import {Form, Modal, Button} from "react-bootstrap";
import {DepartmentFormInput, DepartmentFormProps} from "../types";
import {useForm, Controller} from "react-hook-form";
import API from '../utils/api'
import ErrorMessage from "./ErrorMessage";

const DepartmentForm = ({ formState, handleClose }: DepartmentFormProps) => {
    const {control, handleSubmit, reset, formState: {errors}} = useForm<DepartmentFormInput>({
        defaultValues: {
            name: ''
        }
    })
    const [loading, setLoading] = useState(false)
    const [errorMessage, setErrorMessage] = useState('')

    useEffect(() => {
        reset({
            name: formState.department ? formState.department.name : ''
        })
    }, [formState, reset])

    const submitData = async (data: DepartmentFormInput) => {
        try {
            setLoading(true)
            setErrorMessage('')
            const formData = new FormData();

            formData.set('name', data.name)

            await API.post(
                formState.department ? `/departments/${formState.department.id}` : "/departments/create",
                formData
            )

            setLoading(false)
            doClose(true)
        } catch(e) {
            //console.log('error', e)
            setLoading(false)
            setErrorMessage('Cannot save data')
        }
    }

    const doClose = (load = false) => {
        if(!loading) {
            reset({
                name: ''
            })
            setErrorMessage('')
            handleClose(load)
        }
    }

    return <Modal animation={false} onHide={doClose} show={formState.show}>
        <Modal.Header>
            <Modal.Title>Department</Modal.Title>
        </Modal.Header>
        <Form onSubmit={handleSubmit(submitData)}>
            <Modal.Body>
                <ErrorMessage message={errorMessage} />
                <Form.Group controlId="departmentName">
                    <Form.Label>Department name</Form.Label>
                    <Controller
                        name="name"
                        control={control}
                        rules={{ required: true }}
                        render={({ field, fieldState }) => <Form.Control type="text" placeholder="e.g. Department of Love" isInvalid={fieldState.invalid} {...field} />}
                    />

                    {errors.name && <Form.Control.Feedback type="invalid">
                        Name is required.
                    </Form.Control.Feedback>}
                </Form.Group>
            </Modal.Body>
            <Modal.Footer>
                {!loading && <Button variant="outline-secondary" onClick={() => doClose(false)}>
                    Close
                </Button>}
                <Button type="submit" variant="primary" disabled={loading}>
                    {loading ? 'Saving...' : 'Save Changes'}
                </Button>
            </Modal.Footer>
        </Form>
    </Modal>
}

export default DepartmentForm
