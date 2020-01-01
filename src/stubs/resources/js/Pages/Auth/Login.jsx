import * as React from 'react';
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink } from '@inertiajs/inertia-react';
import Error from "../../Components/FlashMessages/Error";

class Login extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            email: '',
            password: ''
        };
    }

    onSubmit = (e) => {
        e.preventDefault();
        Inertia.post('/login', { ...this.state });
    };

    onChange = (e) => {
        this.setState({ [e.target.name]: e.target.value })
    };

    render() {

        return (
            <div>
                <h1>Login</h1>
                <form onSubmit={this.onSubmit}>
                    <ul>
                        <li>Email</li>
                        <li>
                            <input
                                name="email"
                                className="border"
                                type="text"
                                value={this.state.email}
                                onChange={this.onChange}
                            />
                        </li>
                        <Error errors={this.props.errors} error="email" />
                        <li>Password</li>
                        <li>
                            <input
                                name="password"
                                className="border"
                                type="password"
                                value={this.state.password}
                                onChange={this.onChange}
                            />
                        </li>
                        <Error errors={this.props.errors} error="password" />
                        <li>
                            <InertiaLink
                                className="text-blue-800"
                                href="/password/reset"
                            >
                                Forgot password
                            </InertiaLink>
                        </li>
                        <li>
                            <input className="border" type="submit" />
                        </li>
                    </ul>

                </form>
            </div>
        );
    }
}

export default Login;
