import React from 'react';

const SearchForm = (props) => (
    <div className="row">
        <h1 className="pb-2 pt-2">Search</h1>
        <form method="post" onSubmit={props.handlSearch}>
            <div className="form-row">
                <div className="form-group col-md-2">
                    <label htmlFor="name">Name</label>
                    <input type="text" className="form-control" id="name" placeholder="Name" />
                </div>
                <div className="form-group col-md-2">
                    <label htmlFor="price">Price</label>
                    <div className="form-row">
                        <div className="col-md-6">
                            <input type="number" className="form-control" id="min-price" placeholder="Min" />
                        </div>
                        <div className="col-md-6">
                            <input type="number" className="form-control" id="max-price" placeholder="Max" />
                        </div>
                    </div>
                </div>
                <div className="form-group col-md-2">
                    <label htmlFor="bedrooms">Bedrooms</label>
                    <input type="number" className="form-control" id="bedrooms" placeholder="Bedrooms" />
                </div>
                <div className="form-group col-md-2">
                    <label htmlFor="bathrooms">Bathrooms</label>
                    <input type="number" className="form-control" id="bathrooms" placeholder="Bathrooms" />
                </div>
                <div className="form-group col-md-2">
                    <label htmlFor="storeys">Storeys</label>
                    <input type="number" className="form-control" id="storeys" placeholder="Storeys" />
                </div>
                <div className="form-group col-md-2">
                    <label htmlFor="garages">Garages</label>
                    <input type="number" className="form-control" id="garages" placeholder="Garages" />
                </div>
            </div>
            {false === props.loading &&
                <button className="btn btn-primary">Search</button>
            }
            {true === props.loading &&
                <button className="btn btn-primary" disabled="disabled">Loading</button>
            }
        </form>
    </div>
);

export default SearchForm;