import axios from 'axios';

const DOCUMENTS_ENDPOINT = `/api/documents`;
const TYPES_ENDPOINT = `/api/document_types`;
const AWS_PUBLIC_HOSTNAME = 'http://169.254.169.254/latest/meta-data/public-hostname';

const DocumentService = {
    getAWSPublicHostname() {
        return axios.get(`${AWS_PUBLIC_HOSTNAME}`);
    },
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
