@extends('admin.layouts.master')
@section('title','agency-group edit form')
@section('content')
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Agency Group</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="edit-form">
                @csrf
                <input type="hidden" id="edit-id" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-code">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit-code" name="code" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection