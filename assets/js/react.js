import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';

import SearchForm from './Components/SearchForm';
import SearchResult from './Components/SearchResult';


class App extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            searchResult: undefined,
            loading: false
        };
        this.handleSearch = this.handleSearch.bind(this);
    }

    handleSearch(event) {
        event.preventDefault();
        var name = event.target.elements.name.value,
            minPrice = event.target.elements['min-price'].value,
            maxPrice = event.target.elements['max-price'].value,
            bedrooms = event.target.elements.bedrooms.value,
            bathrooms = event.target.elements.bathrooms.value,
            storeys = event.target.elements.storeys.value,
            garages = event.target.elements.garages.value;

        var postObj = {
            name: name,
            'min-price': minPrice,
            'max-price': maxPrice,
            bedrooms: bedrooms,
            bathrooms: bathrooms,
            storeys: storeys,
            garages: garages,
        };

        this.setState({
            loading: true
        });

        axios.post('/search', postObj)
            .then(response => {
                this.setState({
                    searchResult: response.data,
                    loading: false
                })
            });
    }

    render() {
        var searchResult = undefined;
        if (typeof this.state.searchResult !== "undefined") {
            searchResult = this.state.searchResult.search_result
        }
        return (
            <div>
                <SearchForm
                    handlSearch={this.handleSearch}
                    loading={this.state.loading}
                >
                </SearchForm>
                <SearchResult
                    searchResult={searchResult}
                >
                </SearchResult>
            </div>
        );
    }
}

ReactDOM.render(<App/>, document.getElementById('root'));
