import * as React from 'react';
import { Inertia } from '@inertiajs/inertia';
import Error from "../../Components/FlashMessages/Error";

class PasswordReset extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            token: props[0].token,
            email: props[0].email,
            password: '',
            'password_confirmation': ''
        };
    }

    onSubmit = (e) => {
        e.preventDefault();
        Inertia.post('/password/reset', { ...this.state });
    };

    onChange = (e) => {
        this.setState({ [e.target.name]: e.target.value })
    };

    render() {

        return (
            <div>
                <h1>Reset password</h1>
                <form onSubmit={this.onSubmit}>
                    <ul>

                        <li>Email</li>
                        <li>
                            <input
                                disabled
                                name="email"
                                className="border"
                                type="text"
                                value={this.state.email}
                                onChange={this.onChange}
                            />
                        </li>
                        <Error errors={this.props.errors} error="email"/>
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
                        <Error errors={this.props.errors} error="password"/>

                        <li>Confirm password</li>
                        <li>
                            <input
                                name="password_confirmation"
                                className="border"
                                type="password"
                                value={this.state['password_confirmation']}
                                onChange={this.onChange}
                            />
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

export default PasswordReset;
