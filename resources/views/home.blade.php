@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if(Auth::user()->roleID == 1)
                    <div class="card-header">{{ __('Todo Lists. (Items highlighted in green have been completed. Items in red have been deleted by user.)')}}</div>
                @else
                    <div class="card-header">{{ __('Todo Lists. (Items highlighted in green have been completed.)')}}</div>
                @endif

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createItem">Create New Todo</button>
                        </div>
                        <div style="margin-top:20px;">
                            <div class="row" id="responseMessage"></div>
                            <table id="todosTable" class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- Modal for creating a new todo item -->
                        <div class="modal fade" id="createItem" tabindex="-1" role="dialog" aria-labelledby="createItemModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="createItemModalLabel">Add A New Todo-List</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-danger" id="error_alert" style="display: none" role="alert">
                                        </div>
                                        <form>
                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                            <div class="form-group">
                                                <label for="message-text" class="col-form-label">Todo Item (required):</label>
                                                <textarea class="form-control" id="todo_item" maxlength="255"></textarea>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" id="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <!-- Edit Modal -->
                        <div class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editItemModalLabel">Edit Todo-List</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-danger" id="error_alert" style="display: none" role="alert">
                                        </div>
                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                            <div class="form-group">
                                                <label for="message-text" class="col-form-label">Todo Item (required):</label>
                                                <textarea class="form-control" id="edit_todo_item" maxlength="255"></textarea>
                                                <input type="text" id="todo_item_id" class="d-none" value="">
                                            </div>
                                        </form>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" id="update" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>

</script>
@endsection
