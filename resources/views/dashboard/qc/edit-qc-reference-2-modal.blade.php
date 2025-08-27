<!-- Horizontal form modal -->
<div id="edit-qc-reference-2-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit QC Data (Level 2)</h5>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    X
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body">
                <!-- {!! Form::open(['class'=>'form form-horizontal form-validate-jquery', 'id' => 'form-edit-level-1', 'method' => 'put']) !!} -->
                <div class="edit-patient-details-form">
                    <input type="hidden" id="qc_reference_id_edit_2" name="qc_reference_id_edit_2"  data-qc-reference-id-edit-level-2="" class="form-control form-control-solid form-control-sm">
                    <input type="hidden" id="qc_reference_id_2" data-qc-reference-id-level-2="" name="qc_reference_id" class="form-control form-control-solid form-control-sm">

                    <div class="fv-row row mb-4">
                        <div class="col-md-3"><label class="form-label fs-7">Standard Deviation</label></div>
                        <div class="col-md-9">
                            <!-- {{ Form::number('day_edit', null, ['class' => 'form-control form-control-solid form-control-sm']) }} -->
                            <!-- <input type="text" id="day_edit" name="day_edit" class="form-control form-control-solid form-control-sm"> -->
                            <select class="form-select form-select-sm form-select-solid select-two" data-control="select2" id="edit_standard_deviation2" name="standard_deviation1" data-placeholder="Select Standard Deviation">
                                                    <option value="">Select Standard Deviation</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                </select>
                        </div>
                    </div>
                    <div class="fv-row row mb-4">
                        <div class="col-md-3"><label class="form-label fs-7">Control Name</label></div>
                        <div class="col-md-9">
                            <!-- {{ Form::text('qc_data_edit', null, ['class' => 'form-control form-control-solid form-control-sm']) }} -->
                            <input type="text" id="edit_control_name2" name="control_name2" class="form-control form-control-solid form-control-sm" >
                        </div>
                    </div>
                    <div class="fv-row row mb-4">
                        <div class="col-md-3"><label class="form-label fs-7">Low Value</label></div>
                        <div class="col-md-9">
                            <!-- {{ Form::text('position_edit', null, ['class' => 'form-control form-control-solid form-control-sm']) }} -->
                            <input type="text" id="edit_low_value2" name="low_value2" class="form-control form-control-solid form-control-sm" >
                        </div>
                    </div>
                    <div class="fv-row row mb-4">
                        <div class="col-md-3"><label class="form-label fs-7">High Value</label></div>
                        <div class="col-md-9">
                            <!-- {{ Form::number('qc_edit', null, ['class' => 'form-control form-control-solid form-control-sm']) }} -->
                            <input type="text" id="edit_high_value2" name="high_value2" onfocusout="editonFocusOut2()" class="form-control form-control-solid form-control-sm" >
                        </div>
                    </div>
                    <div class="fv-row row mb-4">
                        <div class="col-md-3"><label class="form-label fs-7">Target Value</label></div>
                        <div class="col-md-9">
                            <!-- {{ Form::text('atlm', null, ['class' => 'form-control form-control-solid form-control-sm']) }} -->
                            <input type="text" id="edit_target_value2" name="target_value2" class="form-control form-control-solid form-control-sm" >
                        </div>
                    </div>
                    <div class="fv-row row mb-4">
                        <div class="col-md-3"><label class="form-label fs-7">1 SD</label></div>
                        <div class="col-md-9">
                            <!-- {{ Form::text('recommendation_edit', null, ['class' => 'form-control form-control-solid form-control-sm']) }} -->
                            <input type="text" id="edit_deviation2" name="deviation2" class="form-control form-control-solid form-control-sm" >
                        </div>
                    </div>

                    <!-- End Input -->
                </div>
                <div class="mb-2 mt-8">
                    <button type="button" id="edit_button_update_data" onclick="updateReference2()" class="form-control btn btn-light-success" data-qc-id-1="" data-qc-id-2="" data-qc-id-3=""> Update Reference</button>
                    <!-- {{ Form::submit('Edit QC Data', ['class' => 'form-control btn btn-light-success']) }} -->
                </div>
                <!-- {!! Form::close() !!} -->
            </div>
        </div>
    </div>
</div>
<!-- /horizontal form modal -->