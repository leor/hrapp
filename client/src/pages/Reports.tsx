import React from "react";
import {Link} from "react-router-dom";
import {reports} from "../const";

const Reports = () => {
    return <>
        <h2>Reports</h2>
        <p>Please, select the report</p>
        <ul>
            {reports.map(report => (
                <li key={report.key}><Link to={`/reports/${report.key}`}>{report.title}</Link> - {report.description}</li>
            ))}
        </ul>
    </>
}

export default Reports

