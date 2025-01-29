import './bootstrap';
import {Alpine, Livewire} from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard';

Alpine.plugin(Clipboard);

Livewire.start();
