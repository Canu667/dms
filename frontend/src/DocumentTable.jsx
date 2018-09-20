import React, { Component } from 'react';
import DocumentService from './DocumentService';
import FileUpload from './FileUpload';

const ALL_VALUE = '--All--';

class DocumentTable extends Component {
    constructor(props) {
        super(props);

        this.state = {
            types: [],
            documents: [],
            instance: ''
        };

        DocumentService.getAWSPublicHostname().then((response) => this.setState({instance: response}));

        this.handleChange = this.handleChange.bind(this);
        this.handleDelete = this.handleDelete.bind(this);
        this.addDocument = this.addDocument.bind(this);
    }

    componentDidMount() {
        DocumentService.getTypes().then((response) => {
            this.setState({types: response.data});
        });
        this.updateDocuments();
    }

    updateDocuments(filterTypeId) {
        DocumentService.getDocuments(filterTypeId).then((response) => {
            this.setState({documents: response.data});
        });
    }

    handleChange(event) {
        const filerTypeId = ALL_VALUE === event.target.value ? null : event.target.value;
        this.updateDocuments(filerTypeId);
    }

    handleDelete(documentId) {
        DocumentService.deleteDocument(documentId).then((response) => {
            const updatedDocuments = this.state.documents.filter((document) => {
                if (document.id === documentId) {
                    return false;
                }
                return true;
            });

            this.setState({documents: updatedDocuments});

        });
    }

    addDocument(document) {
        const updatedDocuments = this.state.documents;
        updatedDocuments.push(document);

        this.setState({documents: updatedDocuments});
    }

    render() {
        return (
            <div className="table-responsive">
                Current instance: {this.instance}
                <table id="documents" className="table table-dark table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>
                                Type
                                <select onChange={this.handleChange} value={this.state.value}>
                                    <option>{ALL_VALUE}</option>
                                    {this.state.types.map((type) => {
                                        return <option
                                            key={type.id}
                                            value={type.id}>{type.mime_type}
                                        </option>
                                    })}
                                </select>
                            </th>
                            <th>Path</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>

                    {
                        this.state.documents.map((document) =>
                            <tr key={document.id}>
                                <td>{document.id}</td>
                                <td>{document.name}</td>
                                <td>{document.type.mime_type}</td>
                                <td>{document.file_name}</td>
                                <td>
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button
                                            className="btn btn-danger"
                                            data-title="Delete"
                                            data-target="#delete"
                                            onClick={() => this.handleDelete(document.id)}
                                        >
                                            <span className="fa fa-trash"></span>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                        )
                    }
                    </tbody>
                </table>
                <div className="clearfix"></div>
                <FileUpload notifyAfterUpload={this.addDocument}/>
            </div>
        );
    }
}

export default DocumentTable;
