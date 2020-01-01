import * as React from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';

class Index extends React.Component {

    render() {

        return (
            <div>
                <h1>Inertia React Boilerplate</h1>

                <InertiaLink href="/login" method='GET'>
                    Login
                </InertiaLink>
            </div>
        );
    }
}

export default Index;
