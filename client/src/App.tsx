import React from 'react';
import {BrowserRouter, Switch, Route} from 'react-router-dom';
import Home from "./pages/Home";
import Navigation from "./components/Navigation";
import {Container} from "react-bootstrap";
import Reports from "./pages/Reports";
import Departments from "./pages/Departments";
import DepartmentView from "./pages/DepartmentView";
import ReportView from "./pages/ReportView";

function App() {
    return (
        <BrowserRouter>
            <Navigation/>
            <Container className="pt-4">
                <Switch>
                    <Route exact path="/">
                        <Home/>
                    </Route>
                    <Route exact path="/departments">
                        <Departments/>
                    </Route>
                    <Route path="/departments/:id" >
                        <DepartmentView/>
                    </Route>
                    <Route exact path="/reports">
                        <Reports/>
                    </Route>
                    <Route path="/reports/:key">
                        <ReportView/>
                    </Route>
                </Switch>
            </Container>
        </BrowserRouter>
    );
}

export default App;
