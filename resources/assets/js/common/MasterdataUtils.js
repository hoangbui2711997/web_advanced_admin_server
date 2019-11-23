import buffer from 'buffer';
import zlib from 'browserify-zlib';
import rf from '../requests/RequestFactory';

const masterdataVersionKey = 'masterdata_version';
const masterdataKey = 'masterdata';

export default class MasterdataUtils {
  static async getOneTable (table) {
    const masterdata = await rf.getRequest('MasterdataRequest').getAll();
    return masterdata[table] || [];
  }
}
