import React from 'react'
import {Button, ButtonGroup} from "react-bootstrap";

import {EmployeeProps} from "../types";

const EmployeeItem = ({employee, onRemove, onEdit}: EmployeeProps) => (
    <tr>
        <td>
            {employee.name}
        </td>
        <td>
            ${employee.salary}
        </td>
        <td className="d-flex justify-content-end">
            <ButtonGroup>
                <Button size="sm" variant="primary" onClick={() => onEdit(employee)}><i className="bi bi-pencil-square"></i> Edit</Button>
                <Button size="sm" variant="danger" onClick={() => onRemove(employee)}><i className="bi bi-trash"></i> Remove</Button>
            </ButtonGroup>
        </td>
    </tr>
)

export default EmployeeItem
