import {useState, useEffect} from 'react';
import {Link} from 'react-router-dom';
import axios from 'axios';
import Alert from 'react-bootstrap/Alert';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import Table from 'react-bootstrap/Table';

const DoRender = () => {
    const [users, setUsers] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [confirmDeleteUsername, setConfirmDeleteUsername] = useState(null);

    useEffect(() => {
        axios.get(process.env.REACT_APP_API_URL + '/users')
            .then(response => {
                if (!response.data.data) {
                    setError(new Error('Um erro desconhecido ocorreu. Por favor, tente novamente.'));
                    return;
                }
                setUsers(response.data.data);
            })
            .catch(setError)
            .finally(() => setLoading(false));
    }, []);

    if (error) {
        return (
            <Alert variant="danger">
                Erro: {error.message}
            </Alert>
        )
    }

    if (loading) {
        return (
            <div className="spinner-border" role="status">
                <span className="visually-hidden">Loading...</span>
            </div>
        );
    }

    if (!users.length) {
        return (
            <Alert variant="info">
                Nenhum usuário cadastrado
            </Alert>
        );
    }

    const deleteUser = (id) => {
        setConfirmDeleteUsername(null);
        setLoading(true);
        axios.delete(process.env.REACT_APP_API_URL + '/users/' + id)
            .then(response => {
                if (!response.data.data) {
                    setError(new Error('Um erro desconhecido ocorreu. Por favor, tente novamente.'));
                    return;
                }
                setUsers(users.filter((user) => user.id !== id));
            })
            .catch(setError)
            .finally(() => setLoading(false));
    };

    return (
        <>
            <Table striped className="align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome de usuário</th>
                        <th>Nome</th>
                        <th></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colSpan="4">
                            <em>
                                {users.length} usuário{(users.length === 1) ? '' : 's'} encontrado{(users.length === 1) ? '' : 's'}
                            </em>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    {users.map((user) => (
                        <tr key={user.id}>
                            <td>{user.id}</td>
                            <td>{user.username}</td>
                            <td>{user.name}</td>
                            <td>
                                <Button variant="outline-danger" size="sm"
                                        onClick={() => setConfirmDeleteUsername(user)}>
                                    Excluir
                                </Button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </Table>
            <Modal show={confirmDeleteUsername !== null} onHide={() => setConfirmDeleteUsername(null)}>
                <Modal.Header closeButton>
                    <Modal.Title>Confirmar exclusão</Modal.Title>
                </Modal.Header>

                <Modal.Body>
                    <p className="text-danger mb-0">
                        Você tem certeza que deseja excluir o usuário <b>{confirmDeleteUsername?.username}</b>? Essa
                        ação não
                        poderá ser desfeita.
                    </p>
                </Modal.Body>

                <Modal.Footer className="justify-content-between">
                    <Button variant="danger" onClick={() => deleteUser(confirmDeleteUsername.id)}>Confirmar
                        exclusão</Button>
                    <Button variant="secondary" onClick={() => setConfirmDeleteUsername(null)}>Fechar</Button>
                </Modal.Footer>
            </Modal>
        </>
    );
}

function UsersList() {
    return (
        <>
            <div className="d-flex justify-content-between align-items-center mb-4">
                <h1>Listagem de usuários</h1>
                <Link to="/users/create" className="btn btn-info">Cadastrar usuário</Link>
            </div>
            {DoRender()}
        </>
    );
}

export default UsersList;
