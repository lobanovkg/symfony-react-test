import React from 'react';

class SearchResult extends React.Component {
    render() {
        var defaultSearchResult = 'Empty search result',
            searchResult = [];

        if (typeof this.props.searchResult !== 'undefined' && Object.entries(this.props.searchResult).length !== 0) {
            defaultSearchResult = undefined;
            for (var key in this.props.searchResult) {
                if (this.props.searchResult.hasOwnProperty(key)) {
                    searchResult.push(<tr>
                        <td>{this.props.searchResult[key].id}</td>
                        <td>{this.props.searchResult[key].name}</td>
                        <td>{this.props.searchResult[key].price}</td>
                        <td>{this.props.searchResult[key].bedrooms}</td>
                        <td>{this.props.searchResult[key].bathrooms}</td>
                        <td>{this.props.searchResult[key].storeys}</td>
                        <td>{this.props.searchResult[key].garages}</td>
                    </tr>)
                }
            }
        }
        return (
            <div className="row">
                <div className="col-md-12">
                    <table className="table mt-5 table-hover">
                        <thead className="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Bedrooms</th>
                            <th scope="col">Bathrooms</th>
                            <th scope="col">Storeys</th>
                            <th scope="col">Garages</th>
                        </tr>
                        </thead>
                        <tbody>
                        {searchResult &&
                            searchResult
                        }
                        {defaultSearchResult &&
                            <tr>
                                <td colSpan="7">{defaultSearchResult}</td>
                            </tr>
                        }
                        </tbody>
                    </table>
                </div>
            </div>
        )
    };
}

export default SearchResult;