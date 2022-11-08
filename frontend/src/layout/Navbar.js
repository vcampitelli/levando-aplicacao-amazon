import {useLocation} from 'react-router';
import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import BsNavbar from 'react-bootstrap/Navbar';
import {LinkContainer} from 'react-router-bootstrap';

function Navbar() {
    const {pathname} = useLocation();
    return (
        <BsNavbar bg="light" expand="lg">
            <Container>
                <BsNavbar.Brand href="/">Minha aplicação</BsNavbar.Brand>
                <BsNavbar.Toggle aria-controls="navbarSupportedContent"/>
                <BsNavbar.Collapse id="navbarSupportedContent">
                    <Nav className="me-auto">
                        <LinkContainer to="/">
                            <Nav.Link active={pathname === "/"}>Início</Nav.Link>
                        </LinkContainer>
                        <LinkContainer to="/users">
                            <Nav.Link active={pathname === "/users" || pathname === "/users/create"}>Usuários</Nav.Link>
                        </LinkContainer>
                    </Nav>
                </BsNavbar.Collapse>
            </Container>
        </BsNavbar>
    );
}

export default Navbar;
