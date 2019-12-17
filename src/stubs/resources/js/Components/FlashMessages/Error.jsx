import React, { Component } from 'react';
import PropTypes from 'prop-types';

export default class Error extends Component {
    render() {
        return this.props.errors[this.props.error] ? this.props.errors[this.props.error][0] : null
    }
}

Error.propTypes = {
    errors: PropTypes.object,
    error: PropTypes.string
};
