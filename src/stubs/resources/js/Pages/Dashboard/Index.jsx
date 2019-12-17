import * as React from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';

class Index extends React.Component {

    render() {

        return (
            <div>
                <h1>Logged as {this.props.user.name}</h1>

                <InertiaLink href="/logout" method='POST'>
                    Logout
                </InertiaLink>
            </div>
        );
    }
}

export default Index;
