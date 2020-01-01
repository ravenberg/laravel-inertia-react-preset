import * as React from 'react';
import { Inertia } from '@inertiajs/inertia';
import Error from "../../Components/FlashMessages/Error";

class ForgotPassword extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            email: '',
        };
    }

    onSubmit = (e) => {
        e.preventDefault();
        Inertia.post('/password/email', { ...this.state });
    };

    onChange = (e) => {
        this.setState({ [e.target.name]: e.target.value })
    };

    render() {

        return (
            <div>
                <h1>Forgot Password?</h1>
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
                        {this.props.flash.status && <li>{this.props.flash.status}</li>}
                        <li>
                            <input className="border" type="submit" />
                        </li>
                    </ul>

                </form>
            </div>
        );
    }
}

export default ForgotPassword;
