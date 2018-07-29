import axios from 'axios';
import config from '../config.js';

const DOCUMENTS_ENDPOINT = `${config.backendApi}/documents`;
const TYPES_ENDPOINT = `${config.backendApi}/document_types`;

const DocumentService = {
    getTypes: function () {
        return axios.get(`${TYPES_ENDPOINT}`);
    },
    getDocuments: function (typeId) {

        return axios.get(`${DOCUMENTS_ENDPOINT}/`);
    },
    deleteDocument: function (documentId) {
        return axios.delete(`${DOCUMENTS_ENDPOINT}/${documentId}`);
    },
    addDocument: function (file) {
        const formData = new FormData();
        formData.append('document[documentUpload]', file);

        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        };

        return axios.post(DOCUMENTS_ENDPOINT, formData, config)
    }
};

export default DocumentService;
