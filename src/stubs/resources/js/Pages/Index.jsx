import * as React from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';

class Index extends React.Component {

    render() {

        return (
            <div>
                <h1>{'InertiaJS + React + Tailwind = 💜'}</h1>

                <InertiaLink href="/login" method='GET'>
                    login
                </InertiaLink>
            </div>
        );
    }
}

export default Index;
