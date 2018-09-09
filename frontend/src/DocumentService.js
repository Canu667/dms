import axios from 'axios';

const DOCUMENTS_ENDPOINT = `${process.env.API_URL}/api/documents`;
const TYPES_ENDPOINT = `${process.env.API_URL}/api/document_types`;

const DocumentService = {
    getTypes: function () {
        return axios.get(`${TYPES_ENDPOINT}`);
    },
    getDocuments: function (typeId) {
        if (typeId) {
            return axios.get(`${TYPES_ENDPOINT}/${typeId}/documents`)
        }
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
