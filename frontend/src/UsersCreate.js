import {useState} from 'react';
import {Link} from 'react-router-dom';
import axios from 'axios';
import Button from 'react-bootstrap/Button';
import Col from 'react-bootstrap/Col';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';
import Row from 'react-bootstrap/Row';

const DoRender = () => {
    const [loading, setLoading] = useState(false);
    const [success, setSuccess] = useState(false);
    const [error, setError] = useState(null);
    const [username, setUsername] = useState('');
    const [name, setName] = useState('');

    if (loading) {
        return (
            <div className="spinner-border" role="status">
                <span className="visually-hidden">Loading...</span>
            </div>
        );
    }

    const onSubmit = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (!username.length) {
            alert('Por favor, preencha o nome do usuário');
            return false;
        }
        if (!name.length) {
            alert('Por favor, preencha o nome do usuário');
            return false;
        }
        setLoading(true);
        axios.post(process.env.REACT_APP_API_URL + '/users', {
            name,
            username,
        })
            .then(response => {
                if (!response?.data?.data?.id) {
                    setSuccess(false);
                    setError(
                        response.data.error || 'Um erro desconhecido ocorreu. Por favor, tente novamente.'
                    );
                    return;
                }
                setSuccess(username);
                setError(false);
            })
            .catch((error) => {
                setSuccess(false);
                setError(error.response?.data?.error?.description || error.message);
            })
            .finally(() => setLoading(false))
    };
    return (
        <>
            <Form className="mb-3" onSubmit={onSubmit}>
                <Row className="align-items-md-center">
                    <Col xs={12} md>
                        <Form.Group controlId="form-username" className="mb-3 mb-md-0">
                            <Form.Label>Nome de usuário</Form.Label>
                            <Form.Control type="text" placeholder="jose.silva" required value={username}
                                          onChange={(e) => setUsername(e.target.value)}/>
                        </Form.Group>
                    </Col>
                    <Col xs={12} md>
                        <Form.Group controlId="form-name" className="mb-3 mb-md-0">
                            <Form.Label>Nome real</Form.Label>
                            <Form.Control type="text" placeholder="José da Silva" required value={name}
                                          onChange={(e) => setName(e.target.value)}/>
                        </Form.Group>
                    </Col>
                    <Col md="auto" className="align-self-end">
                        <Button type="submit" variant="primary">Cadastrar</Button>
                    </Col>
                </Row>
            </Form>
            {(success) ? (
                <div className="alert alert-success">
                    Usuário <b>{success}</b> criado com sucesso
                </div>
            ) : ((error) ? (
                <div className="alert alert-danger">
                    Erro: {error}
                </div>
            ) : null)}
        </>
    );
}

function UsersCreate() {
    return (
        <>
            <div className="d-flex justify-content-between align-items-center mb-4">
                <h1>Cadastro de usuários</h1>
                <Link to="/users" className="btn btn-info">Voltar para listagem</Link>
            </div>
            {DoRender()}
        </>
    );
}

export default UsersCreate;

