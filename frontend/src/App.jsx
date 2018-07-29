import React, { Component } from 'react';
import DocumentTable from './DocumentTable';

class App extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
        <div className="container">
            <div className="row">
                <div className="col-md-12">
                    <h1>Document Management System</h1>
                    <DocumentTable />
                </div>
            </div>
        </div>
        );
    }
}

export default App;
