<?php }elseif ($_GET['id'] == 13){?>
                <div class="block">
                    <div class="header">
                        <h2>TODAY SCHEDULE VISITS</h2>
                    </div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>

                                <th width="20%">STUDY ID</th>
                                <th width="20%">VISIT CODE</th>
                                <th width="25%">STATUS</th>
                                <th width="10%">PHONE NUMBER</th>
                                <th width="20%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($user->data()->position == 1){$a_status='dm_status';}
                            elseif ($user->data()->position == 6){$a_status='sn_status';}
                            elseif ($user->data()->position == 12){$a_status='dc_status';}
                            elseif ($user->data()->position == 5){$a_status='cl_status';}
                            $x=1;foreach ($override->getNews('visit',$a_status,0,'status', 1) as $data){
                                $client=$override->get('clients','id',$data['client_id'])[0];
                                $lastVisit= $override->getlastRow('visit','client_id',$data['client_id'],'visit_date');
                                if($client['status'] == 1){?>
                                    <tr>
                                        <td><?=$client['study_id'].' ( '.$override->get('patient_group','id',$client['pt_group'])[0]['name'].' ) '?></td>
                                        <td><?=$data['visit_code'].' ( '.$data['visit_type'].' ) '?></td>
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                <?php if($data['sn_cl_status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">SN:Pending</button>
                                                <?php }elseif($data['sn_cl_status']==1){?>
                                                    <button class="btn btn-success">SN:Completed</button>
                                                <?php }?>
                                            </div>
                                            <div class="btn-group btn-group-xs">
                                                <?php if($data['dc_status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">DC:Pending</button>
                                                <?php }elseif($data['dc_status']==1){?>
                                                    <button class="btn btn-success">DC:Completed</button>
                                                <?php }?>
                                            </div>
                                            <div class="btn-group btn-group-xs">
                                                <?php if($data['dm_status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">DM:Pending</button>
                                                <?php }elseif($data['dm_status']==1){?>
                                                    <button class="btn btn-success">DM:Completed</button>
                                                <?php }?>
                                            </div>
                                        </td>
                                        <td><?=$client['phone_number']?></td>
                                        <td>
                                            <a href="#appnt<?=$x?>" data-toggle="modal" class="widget-icon" title="Add Visit"><span class="icon-share"></span></a>
                                        </td>
                                        <div class="modal" id="appnt<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">APPOINTMENT</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">VISIT CODE:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="hidden" name="visit_code" value="<?=$client['visit_code']+1?>">
                                                                        <input type="text" name="visit_code" class="form-control" value="<?=$data['visit_code'].'('.$data['visit_type'].')'?>" disabled/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row" id="st">
                                                                    <div class="col-md-2">Status</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" id="site" name="visit_status" required>
                                                                            <option value="">Select Status</option>
                                                                            <option value="1">Complete</option>
                                                                            <option value="2">Missing</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" value="<?=$lastVisit[0]['id']?>">
                                                                <input type="hidden" name="v_id" value="<?=$data['id']?>">
                                                                <input type="hidden" name="client_id" value="<?=$client['id']?>">
                                                                <input type="hidden" name="sn" value="<?=$data['sn_cl_status']?>">
                                                                <input type="submit" name="appointment" value="Submit" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                <?php }$x++;}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }?>










        <a href="info.php?id=3" class="list-group-item"><span class="icon-book"></span>All Schedule Visits<i class="icon-angle-right pull-right"></i></a>
