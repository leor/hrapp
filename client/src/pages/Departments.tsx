import React, {useEffect, useState} from 'react';
import {Button, Container, Spinner, Table} from "react-bootstrap";
import DepartmentItem from "../components/DepartmentItem";
import {Department, DepartmentFormState} from "../types";
import DepartmentForm from "../components/DepartmentForm";
import API from "../utils/api"
import ErrorMessage from "../components/ErrorMessage";

const Departments = () => {
    const [formState, setFormState] = useState<DepartmentFormState>({show: false});
    const [loading, setLoading] = useState(true);
    const [departments, setDepartments] = useState<Department[]>([]);
    const [errorMessage, setErrorMessage] = useState('');

    const loadData = () => {
        setLoading(true)
        API.get("/departments").then(response => {
            setDepartments(response.data ?? [])
            setLoading(false)
        }).catch(e => {
            //console.log('Error loading departments', e)
            setErrorMessage('Cannot load Departments')
        }).finally(() => {
            setLoading(false)
        })
    }

    const removeItem = (id: string) => {
        if(window.confirm('Are you sure you want to remove this Department with all the Employees?')) {
            API.delete(`/departments/${id}`).then(response => {
                loadData()
            }).catch(error => {
                console.log('Error on delete', error)
            })
        }
    }

    const editItem = (department: Department) => {
        setFormState({
            show: true,
            department: department
        })
    }

    useEffect(loadData, [])

    return <>
        <h2 className="d-flex flex-row justify-content-between">
            Department list

            <Button variant="primary" onClick={() => setFormState({show: true})}>
                <i className="bi bi-plus-circle"></i>&nbsp;
                Add Department
            </Button>
        </h2>

        <Container className="pt-4">
            <ErrorMessage message={errorMessage} />
            {loading &&
                <Spinner variant="primary" animation="border"/>
            }
            {!loading && departments.length > 0 &&
            <Table responsive hover borderless size="sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th style={{width: '20%'}}>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    {departments.map(department => <DepartmentItem key={department.id} {...department} onRemove={removeItem} onEdit={editItem} />)}
                </tbody>
            </Table>}
            {!loading && !errorMessage && departments.length === 0 &&
                <h4 className="text-muted text-center pt-5">Departments list is empty. You need to add some data.</h4>
            }
        </Container>

        {<DepartmentForm formState={formState} handleClose={(load = false) => {
            load && loadData()
            setFormState({show: false, department: { id: '', name: '' }})
        }}/>}
    </>
}

export default Departments
