import React, {useEffect, useState} from 'react'
import {Form, Modal, Button, InputGroup} from "react-bootstrap";
import {EmployeeFormInput, EmployeeFormProps} from "../types";
import {useForm, Controller} from "react-hook-form";
import API from '../utils/api'
import ErrorMessage from "./ErrorMessage";

const EmployeeForm = ({ formState, handleClose }: EmployeeFormProps) => {
    const {control, handleSubmit, reset, formState: {errors}} = useForm<EmployeeFormInput>({
        defaultValues: {
            name: '',
            salary: 0
        }
    })
    const [loading, setLoading] = useState(false)
    const [errorMessage, setErrorMessage] = useState('')

    useEffect(() => {
        reset({
            name: formState.employee ? formState.employee.name : '',
            salary:  formState.employee ? formState.employee.salary : 0,
        })
    }, [formState, reset])

    const submitData = async (data: EmployeeFormInput) => {
        try {
            setErrorMessage('')
            setLoading(true)
            const formData = new FormData();

            formData.set('name', data.name)
            formData.set('salary', data.salary.toString())
            formData.set('department_id', formState.employee ? (formState.employee.department_id ?? '').toString() : (formState.department_id ?? '').toString())

            await API.post(
                formState.employee ? `/employees/${formState.employee.id}` : "/employees/create",
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
                <ErrorMessage message={errorMessage}/>
                <Form.Group controlId="employeeName">
                    <Form.Label>Employee name</Form.Label>
                    <Controller
                        name="name"
                        control={control}
                        rules={{ required: true }}
                        render={({ field, fieldState }) => <Form.Control type="text" placeholder="e.g. Mr. Anderson" isInvalid={fieldState.invalid} {...field} />}
                    />

                    {errors.name && <Form.Control.Feedback type="invalid">
                        Name is required.
                    </Form.Control.Feedback>}
                </Form.Group>
                <Form.Group controlId="employeeSalary">
                    <Form.Label>Employee salary</Form.Label>
                    <InputGroup hasValidation={true}>
                        <InputGroup.Prepend>
                            <InputGroup.Text>$</InputGroup.Text>
                        </InputGroup.Prepend>
                        <Controller
                            name="salary"
                            control={control}
                            rules={{ required: true }}
                            render={({ field, fieldState }) => <Form.Control type="number" min={0} placeholder="10000" isInvalid={fieldState.invalid} {...field} />}
                        />
                        {errors.salary && <Form.Control.Feedback type="invalid">
                            Salary is required.
                        </Form.Control.Feedback>}
                    </InputGroup>
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

export default EmployeeForm
