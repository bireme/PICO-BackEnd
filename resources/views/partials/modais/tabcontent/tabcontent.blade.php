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
                <div class="tab-pane fade form-tab-cont {{ $res }}" id="{{$titleid.$count}}-cont" role="tabpanel"
                     data-name="{{$tabtitle}}"
                     aria-labelledby="{{$titleid.$count}}-tab">
                    <div class="container">
                        @if($alternateText!==null)
                            <div class="row">
                                <div class="DontShowButton offset-md-1 secondary-info col-md-10 text-dark font-italic">
                                    <span>{{$alternateText}}</span>
                                </div>
                                <br><br>
                            </div>
                        @endif
                        <div class="row">
                            @foreach($tabdata as $elementid => $elementData)
                                <?php
                                $counttwo++;
                                ?>
                                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 tableModal table-hover">
                                    @if($elementData['checked']!==-1)

                                        <table style="height:100%">
                                            <tbody>
                                            <tr class="descriptorcheckboxrow">
                                                <td>
                                                    @if($elementData['checked']===1)
                                                        <input id="{{$titleid.$counttwo}}"
                                                               class="DescriptorCheckbox descriptorcheckboxclick"
                                                               type="checkbox" checked="" name="{{$elementData['value']}}">
                                                @else
                                                    <input id="{{$titleid.$counttwo}}"
                                                           class="DescriptorCheckbox descriptorcheckboxclick"
                                                           type="checkbox" name="{{$elementData['value']}}">
                                                @endif
                                                </td>
                                                <td><label class="descriptorcheckboxclick"
                                                           for="{{$titleid.$counttwo}}">{{$elementData['title']}}</label>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    @else
                                        <li><span>{{$elementData['value']}}</span></li>
                                    @endif
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
