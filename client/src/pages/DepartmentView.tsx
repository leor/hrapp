import React, {useEffect, useState} from 'react'
import {Button, Container, Spinner, Table} from "react-bootstrap";
import {Employee, EmployeeFormState} from "../types";
import EmployeeItem from "../components/EmployeeItem";
import {useParams} from "react-router-dom";
import API from '../utils/api';
import EmployeeForm from "../components/EmployeeForm";
import ErrorMessage from "../components/ErrorMessage";


const DepartmentView = () => {
    const { id } = useParams<{ id: string }>()
    const [employees, setEmployees] = useState<Employee[]>([])
    const [name, setName] = useState<string>('')
    const [loading, setLoading] = useState(true)
    const [formState, setFormState] = useState<EmployeeFormState>({show: false});
    const [errorMessage, setErrorMessage] = useState('');

    const loadData = (id: string) => {
        setLoading(true)

        API.get(`/departments/${id}`).then(response => {
            setName(response.data.name)
            setEmployees(response.data.employees)
        }).catch(e => {
            //console.log('Error loading', e)
            setErrorMessage('Cannot load department data')
        }).finally(() => {
            setLoading(false)
        })
    }

    const removeItem = (employee: Employee) => {
        if(window.confirm('Are you sure you want to remove this Employee?')) {
            API.delete(`/employees/${employee.id}`).then(() => {
                loadData(employee.department_id)
            }).catch(error => {
                console.log('Error on delete', error)
            })
        }
    }

    const editItem = (employee: Employee) => {
        setFormState({
            show: true,
            employee: employee
        })
    }

    useEffect(() => {
        loadData(id)
    }, [id])

    return <>
        <h2>{name}</h2>

        {loading &&
            <Spinner variant="primary" animation="border"/>
        }

        <Container className="pt-4">
            <ErrorMessage message={errorMessage} />

            {!errorMessage && !loading && <h4 className="d-flex justify-content-between">
                Employees

                <Button variant="primary" onClick={() => setFormState({ show: true, department_id: id })}>
                    <i className="bi bi-plus-circle"></i>&nbsp;
                    Add Employee
                </Button>
            </h4>}
            {!loading && employees.length > 0 &&
                <Table responsive hover borderless size="sm" className="pt-4">
                    <thead>
                        <tr>
                            <th style={{width: '50%'}}>Name</th>
                            <th>Salary</th>
                            <th style={{width: '20%'}}>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    {employees.map(employee => <EmployeeItem key={employee.id} employee={employee} onEdit={editItem} onRemove={removeItem}/>)}
                    </tbody>
                </Table>}
            {!loading && !errorMessage && employees.length === 0 &&
            <h4 className="text-muted text-center pt-5">Employee list is empty. You need to add some data.</h4>
            }

            <EmployeeForm formState={formState} handleClose={(load = false) => {
                load && loadData(id)
                setFormState({show: false})
            }}/>
        </Container>
    </>
}

export default DepartmentView
