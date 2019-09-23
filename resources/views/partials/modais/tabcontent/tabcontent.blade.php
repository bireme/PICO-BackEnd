@spaceless
<form action="">
    <ul class="nav nav-tabs" id="{{$titleid}}" role="tablist">
        <?php
        $count = 0;
        ?>
        @foreach($content as $tabtitle => $tabdata)
            @if(count($tabdata)>0)
                <?php
                $count++;
                if ($count === 1) {
                    $res = 'active show';
                } else {
                    $res = '';
                }
                ?>
                <li class="nav-item">
                    <a class="nav-link form-tab {{ $res }}" id="{{$titleid.$count}}-tab" data-toggle="tab"
                       href="#{{$titleid.$count}}-cont"
                       role="tab"
                       aria-controls="{{$titleid.$count}}-cont" aria-selected="true">{{ $tabtitle }} <span
                            class="badge badge-info">{{count($tabdata)}}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
    <div class="tab-content" id="{{$titleid}}Content">
        <?php
        $count = 0;
        $counttwo = 0;
        ?>
        @foreach($content as $tabtitle => $tabdata)
            @if(count($tabdata)>0)
                <?php
                $count++;
                if ($count === 1) {
                    $res = 'active show';
                } else {
                    $res = '';
                }
                ?>
                <div class="tab-pane fade form-tab-cont {{ $res }}" id="{{$titleid.$count}}-cont" role="tabpanel" data-name="{{$tabtitle}}"
                     aria-labelledby="{{$titleid.$count}}-tab">
                    <div class="container">
                        <div class="row">
                            @foreach($tabdata as $elementid => $elementData)
                                <?php
                                $counttwo++;
                                ?>
                                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 tableModal table-hover">
                                    <table style="height:100%">
                                        <tbody>
                                        <tr class="descriptorcheckboxrow">
                                            <td><input id="{{$titleid.$counttwo}}" class="DescriptorCheckbox descriptorcheckboxclick" type="checkbox"
                                                       @if($elementData['checked'])
                                                           checked
                                                       @endif
                                                       name="{{$elementData['value']}}">
                                            </td>
                                            <td><label class="descriptorcheckboxclick"
                                                       for="{{$titleid.$counttwo}}">{{$elementData['title']}}</label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</form>
@endspaceless
