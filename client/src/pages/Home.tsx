import React from 'react';
import {Jumbotron} from "react-bootstrap";
import {Link} from 'react-router-dom';

const Home = () => (
    <Jumbotron>
        <h1>Hello there</h1>

        <p>This is a simple HR-app I made as a Tech Assesment task. You can do some simple things, using it.</p>

        <ul>
            <li>
                <Link to="departments">Departments</Link> - manage departments and employees
            </li>
            <li>
                <Link to="reports">Reports</Link> - check one of 2 possible reports
            </li>
        </ul>

        <p>Thank you!</p>
    </Jumbotron>
)

export default Home
