import React from 'react'
import DocumentService from './DocumentService';
import Alert from './Alert';

class FileUpload extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            file: null,
            errorMessage: null,
            isError: false
        }
        this.onFormSubmit = this.onFormSubmit.bind(this)
        this.onChange = this.onChange.bind(this)
        this.fileUpload = this.fileUpload.bind(this)
    }

    onFormSubmit(e) {
        e.preventDefault()
        this.fileUpload(this.state.file).then((response) => {
            this.props.notifyAfterUpload(response.data);
            this.setState({
                isError: false
            });
        }).catch((e) => {
            if (e.response) {
                console.log(e.response.data.message);
                this.setState({
                    isError: true,
                    errorMessage: e.response.data.message
                });
            }
        })
    }

    onChange(e) {
        this.setState({
            file: e.target.files[0]
        });
    }

    fileUpload(file) {
        return DocumentService.addDocument(file);
    }

    render() {
        return (
            <div>
                <h1>File Upload</h1>
                <form onSubmit={this.onFormSubmit}>
                    {this.state.isError ? <Alert message={this.state.errorMessage}/> : ''}
                    <div className="form-group">
                        <input type="file" onChange={this.onChange}/>
                    </div>
                    <button type="submit" className="btn btn-primary">Upload</button>
                </form>
            </div>
        )
    }
}

export default FileUpload
