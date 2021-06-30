import React from "react";
import {Navbar, Nav, Container} from "react-bootstrap";
import {Link} from "react-router-dom"

const Navigation = () => (
    <Navbar bg="dark" variant="dark" expand="md">
        <Container>
            <Navbar.Brand href="/">HR App</Navbar.Brand>
            <Navbar.Toggle aria-controls="main-navbar-nav" />
            <Navbar.Collapse id="main-navbar-nav">
                <Nav className="mr-auto">
                    <Link to="/departments" className="nav-link">Departments</Link>
                    <Link to="/reports" className="nav-link">Reports</Link>
                </Nav>
            </Navbar.Collapse>
        </Container>
    </Navbar>
);

export default Navigation
