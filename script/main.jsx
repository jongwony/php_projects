// main.js
import React from 'react';
import ReactDOM from 'react-dom';

import _ from 'lodash';
import $ from 'jquery';

const RaisedButton = require('material-ui/lib/raised-button');

class App extends React.Component
{
    componentDidMount() {
    
    }
    handleClick() {
        alert("Click!");
    }
    render() {
        return (
            <div className="page page-home">
                <h1>Hello Festival!</h1>
                <RaisedButton label="Login" secondary={true} onClick={this.handleClick} />
            </div>
        );
    }
}

ReactDOM.render(<App source="/api/home" />, document.getElementById('FestivalApp'));
