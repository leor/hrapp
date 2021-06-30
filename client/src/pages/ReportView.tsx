import React, {useEffect, useState} from 'react'
import {useParams, Link} from "react-router-dom";
import {reports} from "../const";
import {ReportData} from "../types";
import API from '../utils/api';
import {Spinner, Table} from "react-bootstrap";
import ErrorMessage from "../components/ErrorMessage";

const ReportView = () => {
    const { key } = useParams<{key: string}>()
    const [reportData, setReportData] = useState<ReportData>({title: '', description: '', dataUrl: ''})
    const [loading, setLoading] = useState(true)
    const [errorMessage, setErrorMessage] = useState('')

    useEffect(() => {
        const report = reports.find(r => r.key === key) ?? {title: '', description: '', dataUrl: ''}

        if(report.dataUrl) {
            API.get(report.dataUrl)
                .then(response => {
                    setReportData({
                        ...report,
                        entities: response.data || []
                    })
                })
                .catch(e => setErrorMessage('Cannot load report data.'))
                .finally(() => setLoading(false))
        } else {
            console.log('Report has no url', report)
        }
    }, [key])

    return <>
        <h2>{reportData.title}</h2>
        <p>{reportData.description}</p>

        {loading &&
            <Spinner variant="primary" animation="border"/>
        }

        <ErrorMessage message={errorMessage}/>

        {!loading && reportData.entities && reportData.entities.length > 0 &&
        <Table hover striped size="sm">
            <thead>
                <tr>
                    <th>Department name</th>
                    {'max_salary' in reportData.entities[0] && <th>Max salary</th>}
                    {'rich_count' in reportData.entities[0] && <th>Rich Employees</th>}
                    {'employee_count' in reportData.entities[0] && <th>Total Employees</th>}
                </tr>
            </thead>
            <tbody>
            {reportData.entities.map(entity =>
                <tr key={entity.id}>
                    <td>
                        <Link to={`/departments/${entity.id}`}>{entity.name}</Link>
                    </td>
                    {'max_salary' in entity && <td>${entity.max_salary}</td>}
                    {'rich_count' in entity && <td>{entity.rich_count}</td>}
                    {'employee_count' in entity && <td>{entity.employee_count}</td>}
                </tr>
            )}
            </tbody>
        </Table>
        }
        {!loading && reportData.entities && reportData.entities.length === 0 &&
        <h4 className="text-muted text-center pt-5">Data for report is empty</h4>
        }
    </>
}

export default ReportView
