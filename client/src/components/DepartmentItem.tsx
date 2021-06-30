import React from 'react'
import {Button, ButtonGroup} from "react-bootstrap";
import {Link} from "react-router-dom";

import {DepartmentProps} from "../types";

const DepartmentItem = ({id, name, onRemove, onEdit}: DepartmentProps) => (
    <tr>
        <td>
            <Link to={`/departments/${id}`}>{name}</Link>
        </td>
        <td className="d-flex justify-content-end">
            <ButtonGroup>
                <Button size="sm" variant="primary" onClick={() => onEdit({id, name})}><i className="bi bi-pencil-square"></i> Edit</Button>
                <Button size="sm" variant="danger" onClick={() => onRemove(id)}><i className="bi bi-trash"></i> Remove</Button>
            </ButtonGroup>
        </td>
    </tr>
)

export default DepartmentItem
